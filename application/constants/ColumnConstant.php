<?php
namespace ColumnConstant;

defined('BASEPATH') OR exit('No direct script access allowed');

class CompanyProfile {
    const ID = 'id';
    const NAME = 'name';
    const ADDR = 'addr';
    const EMAIL = 'email';
    const PHONE = 'phone';
}

class Customer {
    const ID = 'id';
    const FULLNAME = 'fullname';
    const ADDR = 'addr';
    const PHONE = 'phone';
}

class Employee {
    const ID = 'id';
    const FULLNAME = 'fullname';
    const USERNAME = 'username';
    const EMAIL = 'email';
    const ADDR = 'addr';
    const PHONE = 'phone';
    const PASSWD = 'passwd';
    const ROLE = 'role';
}

class LaundryPackage {
    const ID = 'id';
    const NAME = 'name';
    const PRICE = 'price';
}

class PaymentType {
    const ID = 'id';
    const NAME = 'name';
}

class Sale {
    const ID = 'id';
    const SALES_NUMBER = 'sales_number';
    const CUSTOMER_ID = 'customer_id';
    const PHONE = 'phone';
    const ADDR = 'addr';
    const LAUNDRY_PACKAGE_ID = 'laundry_package_id';
    const LAUNDRY_PACKAGE_PRICE = 'laundry_package_price';
    const WEIGHT = 'weight';
    const PICKUP_DATE = 'pickup_date';
    const PAYMENT_TYPE_ID = 'payment_type_id';
    const PAYMENT_STATUS = 'payment_status';
    const TOTAL = 'total';
    const ORDER_STATUS = 'order_status';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}