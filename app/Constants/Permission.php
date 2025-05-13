<?php

namespace App\Constants;

class Permission
{
    public const MANAGE_USERS = 'manage_users';
    public const MANAGE_ROLES = 'manage_roles';
    public const  VIEW_PERMISSIONS = 'view_permissions';
    public const MANAGE_CATEGORIES = 'manage_categories';
    public const MANAGE_PRODUCTS = 'manage_products';
    public const ADD_SALES = 'add_sales';
    public const MANAGE_SALES_DELIVERY = 'manage_sales_delivery';
    public const VIEW_SALES = 'view_sales';
    public const VIEW_PURCHASES = 'view_purchases';
    public const ADD_PURCHASE = 'add_purchase';
    public const MANAGE_SUPPLIERS = 'manage_suppliers';
    public const MANAGE_STOCK = 'manage_stock';
    public const MANAGE_STOCK_ADJUSTMENT = 'manage_stock_adjustment';
    public const VIEW_STOCK_ADJUSTMENT = 'view_stock_adjustment';
    public const VIEW_STOCK_MOVEMENT = 'view_stock_movement';
    const MANAGE_CUSTOMERS = 'manage_customers';
    const MANAGE_PAYMENT_METHODS = 'manage_payment_methods';

    const VIEW_REPORTS = 'view_reports';
    const VIEW_SALES_REPORTS = 'view_sales_reports';
    const VIEW_STOCK_REPORTS = 'view_stock_reports';
    const VIEW_PURCHASE_REPORTS = 'view_purchase_reports';
    const ADD_SALE_PAYMENT = 'add_sale_payment';
    const VIEW_SALES_PAYMENTS = 'view_sales_payments';
    const VIEW_SALES_PAYMENT_REPORTS = 'view_sales_payment_reports';
    const VIEW_ITEMS_REPORTS = 'view_items_reports';
    const CANCEL_SALES_ORDERS = 'cancel_sales_orders';
    const MANAGE_EXPENSE_CATEGORIES = 'manage_expense_categories';
    const MANAGE_EXPENSES = 'manage_expenses';
    const VIEW_EXPENSES_REPORTS = 'view_expenses_reports';

    public static function ManageSettings(): array
    {
        return [
            self::MANAGE_SUPPLIERS,
            self::MANAGE_CUSTOMERS,
            self::MANAGE_PAYMENT_METHODS,
            self::MANAGE_EXPENSE_CATEGORIES
        ];
    }

    public static function all(): array
    {
        return [
            self::MANAGE_USERS,
            self::VIEW_PERMISSIONS,
            self::MANAGE_ROLES,
            self::VIEW_PERMISSIONS,
            self::MANAGE_CATEGORIES,
            self::MANAGE_PRODUCTS,
            self::ADD_SALES,
            self::MANAGE_SUPPLIERS,
            self::MANAGE_STOCK,
            self::MANAGE_STOCK_ADJUSTMENT,
            self::VIEW_STOCK_ADJUSTMENT,
            self::VIEW_STOCK_MOVEMENT,
            self::MANAGE_CUSTOMERS,
            self::MANAGE_SALES_DELIVERY,
            self::VIEW_SALES,
            self::VIEW_PURCHASES,
            self::ADD_PURCHASE,
            self::MANAGE_SUPPLIERS,
            self::ADD_SALE_PAYMENT,
            self::VIEW_SALES_PAYMENTS,
            self::VIEW_SALES_PAYMENT_REPORTS,
            self::MANAGE_PAYMENT_METHODS,
            self::VIEW_ITEMS_REPORTS,
            self::CANCEL_SALES_ORDERS,
            self::VIEW_SALES_REPORTS,
            self::VIEW_PURCHASE_REPORTS,
            self::MANAGE_EXPENSE_CATEGORIES,
            self::MANAGE_EXPENSES,
            self::VIEW_EXPENSES_REPORTS
        ];
    }

    public static function ManageProducts(): array
    {
        return [
            self::MANAGE_PRODUCTS,
            self::MANAGE_CATEGORIES
        ];
    }

    public static function ManageSalesOrders(): array
    {
        return [
            self::ADD_SALES,
            self::MANAGE_SALES_DELIVERY
        ];
    }


    public static function ManageStock(): array
    {
        return [
            self::MANAGE_STOCK,
            self::MANAGE_STOCK_ADJUSTMENT,
            self::VIEW_STOCK_ADJUSTMENT,
            self::VIEW_STOCK_MOVEMENT
        ];
    }

    public static function managePurchaseOrders(): array
    {
        return [
            self::VIEW_PURCHASES,
            self::ADD_PURCHASE
        ];
    }

    public static function viewReports(): array
    {
        return [
            self::VIEW_REPORTS,
            self::VIEW_SALES_REPORTS,
            self::VIEW_STOCK_REPORTS,
            self::VIEW_PURCHASE_REPORTS,
            self::VIEW_SALES_PAYMENT_REPORTS,
            self::VIEW_ITEMS_REPORTS

        ];
    }

    public static function ManageSalesPayments(): array
    {
        return [
            self::ADD_SALE_PAYMENT,
            self::VIEW_SALES_PAYMENTS,
        ];
    }


}
