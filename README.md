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

The very first thing you will need to do is to generate your public and private
keys. It is very important that you do not lose these keys and keep them in a
safe place.

```bash
bitpay keygen
```

## Pairing Keys

If you have not already generated keys, please do so now.

Log into you BitPay merchant account and goto the API Tokens page. Once there
you will need to click the button that says "Add New Token". You will have a
spiffy new Pairing Code that you will need to use for this step.

Copy the Pairing Code that was generated on the API Tokens page in your
merchant account. Next run the command:

```bash
bitpay pair PairinCode
```

Once this is done, you will be able to access your account related information.

## Create a New Merchant Account

In the case you do not have a merchant account, you may request one using this
command line tool. Run the command:

```bash
bitpay application
```

And just answer the questions that are asked.

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
