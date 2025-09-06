const fs = require('fs');
const path = require('path');

function cleanComments(content) {
  // Remove single line comments but keep console.log
  let cleaned = content.replace(/^\s*\/\/.*$/gm, '');
  
  // Remove multi-line comments /* */
  cleaned = cleaned.replace(/\/\*[\s\S]*?\*\//g, '');
  
  // Remove JSDoc comments /** */
  cleaned = cleaned.replace(/\/\*\*[\s\S]*?\*\//g, '');
  
  // Remove inline comments but keep console.log lines
  cleaned = cleaned.split('\n').map(line => {
    // If line contains console.log, keep it as is
    if (line.includes('console.')) {
      return line;
    }
    
    // Remove inline comments from other lines
    const commentIndex = line.indexOf('//');
    if (commentIndex !== -1) {
      // Check if // is inside a string literal
      const beforeComment = line.substring(0, commentIndex);
      const singleQuotes = (beforeComment.match(/'/g) || []).length;
      const doubleQuotes = (beforeComment.match(/"/g) || []).length;
      const backticks = (beforeComment.match(/`/g) || []).length;
      
      // If quotes are balanced, remove comment
      if (singleQuotes % 2 === 0 && doubleQuotes % 2 === 0 && backticks % 2 === 0) {
        return line.substring(0, commentIndex).trimEnd();
      }
    }
    
    return line;
  }).join('\n');
  
  // Remove empty lines (more than 2 consecutive)
  cleaned = cleaned.replace(/\n\s*\n\s*\n/g, '\n\n');
  
  return cleaned;
}

// Read customizeLayout1.js
const layout1Path = '/var/www/html/FotoboxJO/src/pages/customizeLayout1.js';
const content1 = fs.readFileSync(layout1Path, 'utf8');
const cleaned1 = cleanComments(content1);

// Write cleaned version
fs.writeFileSync(layout1Path, cleaned1, 'utf8');

console.log('✅ Comments removed from customizeLayout1.js');
console.log(`Original: ${content1.split('\n').length} lines`);
console.log(`Cleaned: ${cleaned1.split('\n').length} lines`);
console.log(`Reduced by: ${content1.split('\n').length - cleaned1.split('\n').length} lines`);
