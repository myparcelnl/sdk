<?php

namespace MyParcelNL\Sdk\Client\Generated\Jane\IamApi\Model;

enum RoleUser : string
{
    case AdminCs = 'ADMIN_CS';
    case AdminCsAdvanced = 'ADMIN_CS_ADVANCED';
    case AdminFinance = 'ADMIN_FINANCE';
    case AdminItSupport = 'ADMIN_IT_SUPPORT';
    case AdminSales = 'ADMIN_SALES';
    case AdminSuper = 'ADMIN_SUPER';
    case CustomerAdmin = 'CUSTOMER_ADMIN';
    case CustomerFinancial = 'CUSTOMER_FINANCIAL';
    case CustomerFunctionalManager = 'CUSTOMER_FUNCTIONAL_MANAGER';
    case CustomerMain = 'CUSTOMER_MAIN';
    case CustomerMarketing = 'CUSTOMER_MARKETING';
    case CustomerService = 'CUSTOMER_SERVICE';
    case CustomerSub = 'CUSTOMER_SUB';
    case CustomerWarehouse = 'CUSTOMER_WAREHOUSE';
}