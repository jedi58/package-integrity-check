# file-integrity-check
Checks the integrity of packages using `sha256`digests.

## Create Checksum Manifrst
To create a new checksum you can use the following:

```bash
./app/console create --path=/path/to/package
```

If you ommit the `path` option then it will default to the current folder.


## Verify Checksum Manifest
To verify a folder against a checksum manifest (the `.json` file must currently be in the folder the application is run from) you can use:

```bash
./app/console verify --path=/path/to/package
```

By default this will tell you how many files have passed or failed verification. If you would like a list of non-matching files then use the `-v` switch to create a more verbose output.
