bitpay/php-client-cli
=====================

Command Line Interface to BitPay's cryptographically secure API.

# Installation

## Global

```bash
composer.phar global require "bitpay/php-client-cli ~2.0@dev"
```

More information about installing composer globally:

* https://getcomposer.org/doc/00-intro.md#globally
* https://getcomposer.org/doc/03-cli.md#global

## Local

```bash
composer.phar require "bitpay/php-client-cli ~2.0"
```

# Usage

This usage guide assumes you have this globally installed and can be ran by
running the command `bitpay`. If you are including this in a project or you
have installed this package some other place, you will need to run the script
from there.

## Generating Keys

```bash
bitpay keygen
```

## Pairing Keys

## Create a New Merchant Account

## Invoices
### Generate a New Invoice
### Get Invoice Information
### List Invoices
### Resend IPN

## Refunds
### Create a Refund Request
### Get the Status of a Refund Request
### Cancel a Pending Refund Request

## Bills
### Create a New Bill
### List Bills
### Get a Bill
### Update a Bill

## Get Ledger Data

## Payouts
### Create a New Payout
### Get Payouts
### Delete a Pending Payout

## Get Currencies BitPay Supports

## Get Current Exchange Rates

# Configuration

Default configuration lives at `~/.bitpay/config.json`. All data is also stored
inside this directory.

# License

MIT
