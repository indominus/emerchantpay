# eMerchantPay Task
Blog Implementation

# Installation

### Install composer dependencies, yarn dependencied and build assets
```bash
yarn install
```
```bash
yarn run build
```
```bash
composer install
```

### Copy .env.example file and edit it
```bash
cp .env.example .env
```

### Import sql backup tables and data (demo user is demo:demo)
```bash
mysql -uroot -p emerchantpay < emerchantpay.sql
```

### Run project
```bash
php -S 0.0.0.0:8888 -t public
```

### Open administration 
### http://localhost:8888/admin
