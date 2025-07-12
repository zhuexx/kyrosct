const mysql = require('mysql2/promise');
const config = require('../includes/config');

module.exports = async (req, res) => {
  if (req.headers.authorization !== config.ADMIN_TOKEN) {
    return res.status(401).json({ error: 'Invalid token' });
  }

  const { hwid = null, expiry = 30 } = req.body;
  const key = 'TINY-' + Math.random().toString(36).slice(2, 6).toUpperCase() + 
              '-' + Math.random().toString(36).slice(2, 6).toUpperCase();

  try {
    const conn = await mysql.createConnection({
      host: config.DB_HOST,
      user: config.DB_USER,
      password: config.DB_PASS,
      database: config.DB_NAME,
      ssl: { rejectUnauthorized: true }
    });

    await conn.execute(
      `INSERT INTO keys (key_value, hwid_lock, expires_at) 
       VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? DAY))`,
      [key, hwid, expiry]
    );

    res.json({ 
      key, 
      expires: new Date(Date.now() + expiry * 86400000).toISOString() 
    });
  } catch (e) {
    res.status(500).json({ error: 'Database error' });
  }
};