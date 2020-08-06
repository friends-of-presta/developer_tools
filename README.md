# Developer tools

Make developer experience for Theme and Module developers great again!

## Features

### Hooks Display
This module enables - once installed and enabled - the display of every hook available in PrestaShop.

It works both on Back and Front office. As you can assume this is really useful for every Theme and Module developer,
to figure out exactly where and what do we have to override or extend.

### Grid dev tools
This feature can be disabled in the Performance page of the Back Office (in Advanced Parameters).

### Employee impersonation

This module also enable complete developer tools for the modern grids, see captures.

> This feature is only available in the Back Office.

## PrestaShop version

This module is available for PrestaShop 1.7.5+.

## Some captures

### Back Office

![Back Office](https://i.imgur.com/Rvk6sfg.png)

![Grid dev toolbar](https://user-images.githubusercontent.com/1247388/58739700-07645280-840c-11e9-9b38-4b6082e44c0e.png)

![Grid Columns collector](https://user-images.githubusercontent.com/1247388/58739701-07645280-840c-11e9-823e-28ecb3bd7e8e.png)

![Grid Hooks Templating](https://user-images.githubusercontent.com/1247388/58739703-07fce900-840c-11e9-8ba0-9215bf241a7e.png)

![User selector](https://user-images.githubusercontent.com/1247388/58739704-07fce900-840c-11e9-8787-a5844c2a4652.png)


### Front Office

![Front Office](https://i.imgur.com/cvizNCp.png)

Also, the Legacy Profiler is working as expected (ie like in PrestaShop 1.6) once enabled:

![Welcome Back, Legacy Profiler](https://user-images.githubusercontent.com/1247388/81765157-60b97a80-94d3-11ea-9236-6730ae18d5c9.PNG)

## Installation

```
composer require "friends-of-prestashop/developer_tools" && 
composer install &&
php bin/console pr:mo install developer_tools
```
