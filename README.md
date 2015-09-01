# Message Processor

* Author: Darragh Enright <darraghenright@gmail.com>
* Date: 2015-08-31

## Description

As a component of the **Market Trade Processor**, the **Message Processor** processes and serves data consumed by **Message Consumer** for frontend display in **Message Frontend**.

The Message Processor is a series of simple scripts that retrieves, modifies and formats data as JSON for visual display.

## Implementation

### Development

* Clone the repository
* Copy `config.ini.dist` to `config.ini` and add local database credentials for Message Consumer.

This component attempts to read environmental variables - once these are set in a production environment, manual configuration is not required. This component requires PHP version >= `5.6`.

To serve locally, run PHP local server in the project root directory, on a port of your choice; e.g:

```
php -S localhost:9898
```
