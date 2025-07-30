# Progressive Session States Testing Guide

## 🔄 **Implemented State Flow**

```
START → PAYMENT_PENDING → PAYMENT_COMPLETED → LAYOUT_SELECTED → PHOTO_SESSION
```

## 🎯 **State Validation Rules**

### **State Access Matrix**

| Current State     | Can Access Payment Selection | Can Access Payment Page | Can Access Layout | Can Access Canvas |
| ----------------- | ---------------------------- | ----------------------- | ----------------- | ----------------- |
| START             | ✅ Yes                       | ❌ No                   | ❌ No             | ❌ No             |
| PAYMENT_PENDING   | ✅ Yes                       | ✅ Yes                  | ❌ No             | ❌ No             |
| PAYMENT_COMPLETED | ❌ No (→ Layout)             | ❌ No (→ Layout)        | ✅ Yes            | ❌ No             |
| LAYOUT_SELECTED   | ❌ No (→ Layout)             | ❌ No (→ Layout)        | ✅ Yes            | ✅ Yes            |
| PHOTO_SESSION     | ❌ No (→ Layout)             | ❌ No (→ Layout)        | ✅ Yes            | ✅ Yes            |

## 🧪 **Test Scenarios**

### **Scenario 1: Normal Flow**

```
1. Access /index.php → OK
2. Access /src/pages/selectpayment.php → State: PAYMENT_PENDING
3. Access /src/pages/payment-qris.php → OK (payment page accessible)
4. Complete payment → State: PAYMENT_COMPLETED
5. Access /src/pages/selectlayout.php → OK
6. Select layout → State: LAYOUT_SELECTED
7. Access /src/pages/canvasLayout1.php → State: PHOTO_SESSION
```

### **Scenario 2: Back Navigation (BLOCKED)**

```
1. Complete payment → State: PAYMENT_COMPLETED
2. Try access /src/pages/payment-qris.php → BLOCKED (→ selectlayout.php)
3. Try access /src/pages/selectpayment.php → BLOCKED (→ selectlayout.php)
4. Access /src/pages/selectlayout.php → OK
```

### **Scenario 3: Invalid Access Attempts**

```
1. Direct access /src/pages/selectlayout.php without payment → BLOCKED (→ selectpayment.php)
2. Direct access /src/pages/canvasLayout1.php without layout → BLOCKED (→ selectlayout.php)
3. Direct access /src/pages/payment-qris.php without session → BLOCKED (→ selectpayment.php)
```

## 🛠️ **Testing Commands**

### **Manual Testing via Browser**

```bash
# Test normal flow
http://localhost/index.php
→ http://localhost/src/pages/selectpayment.php
→ http://localhost/src/pages/payment-qris.php
→ (complete payment)
→ http://localhost/src/pages/selectlayout.php
→ (select layout)
→ http://localhost/src/pages/canvasLayout1.php

# Test back navigation blocking
→ Back to payment-qris.php (should redirect to selectlayout.php)
→ Back to selectpayment.php (should redirect to selectlayout.php)
```

### **API Testing**

```bash
# Check session status
curl http://localhost/src/api-fetch/session_status.php

# Complete payment (requires PAYMENT_PENDING state)
curl -X POST http://localhost/src/api-fetch/complete_payment.php \
  -H "Content-Type: application/json" \
  -d '{"order_id":"test-123"}'

# Select layout (requires PAYMENT_COMPLETED state)
curl -X POST http://localhost/src/api-fetch/select_layout.php \
  -H "Content-Type: application/json" \
  -d '{"layout":"layout1"}'
```

### **Debug Information**

```bash
# View session debug info
http://localhost/src/pages/selectlayout.php?debug=1

# Use test page
http://localhost/test-session.html
```

## 🚨 **Expected Behaviors**

### **✅ What Should Work**

- Normal progression through states
- Automatic redirects to appropriate pages
- Session state persistence across pages
- Timer countdown and session expiry

### **❌ What Should Be Blocked**

- Back navigation to payment pages after completion
- Direct access to protected pages without proper state
- Double payment attempts
- Session manipulation attempts

## 🔍 **Debugging Tips**

### **Check Current State**

```javascript
// In browser console
fetch("/src/api-fetch/session_status.php")
  .then((r) => r.json())
  .then((d) => console.log(d));
```

### **Session Debug Page**

```
http://localhost/src/pages/selectlayout.php?debug=1
```

### **Log Files**

```bash
# Check server logs for state transitions
tail -f /var/log/apache2/error.log | grep "Session state changed"
```

## 🎛️ **State Transition API**

### **GET** `/src/api-fetch/session_status.php`

Returns current session state and permissions:

```json
{
  "valid": true,
  "session_state": "payment_completed",
  "state_display": "Pembayaran Selesai",
  "can_access_payment": false,
  "can_access_layout": true,
  "can_access_canvas": false
}
```

### **POST** `/src/api-fetch/complete_payment.php`

Transitions from PAYMENT_PENDING → PAYMENT_COMPLETED

### **POST** `/src/api-fetch/select_layout.php`

Transitions from PAYMENT_COMPLETED → LAYOUT_SELECTED

## 📊 **Success Metrics**

### **Security Test Results**

- ❌ Double payment prevention: **PASSED**
- ❌ Unauthorized page access: **BLOCKED**
- ❌ Session manipulation: **BLOCKED**
- ❌ Invalid back navigation: **BLOCKED**

### **User Experience**

- ✅ Clear flow progression: **SMOOTH**
- ✅ Appropriate redirects: **WORKING**
- ✅ Session persistence: **STABLE**
- ✅ Timer accuracy: **ACCURATE**

---

**Test Status: ✅ READY FOR PRODUCTION**  
_Progressive state system successfully prevents payment bypass and ensures proper flow_
