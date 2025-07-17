const nodemailer = require("nodemailer");

exports.sendEmail = async (req, res) => {
  const { to, photoUrls } = req.body;
  let transporter = nodemailer.createTransport({
    service: "gmail",
    auth: {
      user: process.env.EMAIL_USER,
      pass: process.env.EMAIL_PASS
    }
  });

  const attachments = photoUrls.map(url => ({
    filename: url.split("/").pop(),
    path: url
  }));

  await transporter.sendMail({
    from: '"Photobooth" <no-reply@photobooth.com>',
    to,
    subject: "Hasil Foto Anda",
    text: "Terima kasih sudah menggunakan photobooth kami!",
    attachments
  });

  res.json({ message: "Email sent" });
};
