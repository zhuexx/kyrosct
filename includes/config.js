module.exports = {
  DB_HOST: process.env.DB_HOST || 'mysql-host',
  DB_USER: process.env.DB_USER || 'user',
  DB_PASS: process.env.DB_PASS || 'password',
  DB_NAME: process.env.DB_NAME || 'dbname',
  ADMIN_TOKEN: process.env.ADMIN_TOKEN || 'default-secret'
};