# Yii2 Excel Template SQL Export Module

## Overview

The **Yii2 Excel Template SQL Export** module allows you to export the results of raw SQL queries directly into an Excel file. This module is built on the Yii2 framework and leverages the [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/) library to handle Excel file creation. Additionally, it provides a user-friendly graphical interface for managing SQL queries.

## Features

- Export multiple SQL queries into a single Excel file.
- Dynamic worksheet creation based on SQL query results.
- Option to filter exported queries using specific IDs.
- Exception handling for graceful error responses.
- GUI for easy management of SQL queries, including adding, editing, and deleting queries.

![](https://github.com/strtob/yii2-excel-template-sql-export/blob/main/Screenshot.png)

## Installation

To install this module, follow these steps:

**Add the module to your project**:
   Use Composer to install the module. Run the following command in your terminal:

```bash
   composer require strtob/yii2-excel-template-sql-export
```

create the tables via migration

```bash
php yii migrate --migrationPath=@vendor/strtob/yii2-excel-template-sql-export/migrations
php yii migrate
```

or

```bash
'components' => [
    // Other components...
    'migrator' => [
        'class' => 'yii\db\Migration',
        'migrationPath' => [          
            '@vendor/strtob/yii2-excel-template-sql-export/migrations', // Add your module's migrations
        ],
    ],
],
```

and adjust your config

```bash
 'modules' => [
        'export' => [
            'class' => \strtob\yii2ExcelTemplateSqlExport\Module::class,
        ],
```
