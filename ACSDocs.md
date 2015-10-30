This api is incomplete and only supports some basic functions.
It was intended to work with hardware with few resources which is why there is one endpoint and some of the fields and messages are very terse.

To use it a valid device id needs to be supplied

__Request__

	POST bbms.buildbrighton.com/acs

Field     | Required | Value
--------- | -------- | -----
device    | yes      | The id specific to the current ACS device
service   | yes      | entry, usage, consumable, shop, status, device-scanner, sensor
message   | yes      | boot, heartbeat, lookup, start, stop, charge, error, update
tag       | yes*     | RFID Tag Number, needed for lookup, start, stop, charge
time      |          | Unix timestamp
signature |          |
nonce     |          |


__Response__

_200_

```
{
	"vaild": "1",
	"cmd": "Bearer",
	"member": 7200,
	"time": 01234567890
}
```

Field  | Value
------ | -----
valid  | Did the request match a real user, used for lookup, start, stop and charge messages
cmd    | Any commands being passed down to the acs device, i.e. purge, disable, etc...
member | A display name for the current member if any
time   | The current unix timestamp, useful for setting internal clocks