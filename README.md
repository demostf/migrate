# Migrate

Script to migrate demos to cold storage

## Usage

The migrate scripts expects demos to be stored in the format as created by the [backup script](https://github.com/demostf/backup)

The following environment variables are required for the script

- SOURCE: The url of the api to backup the demos from (https://api.demos.tf)
- STORAGE_ROOT: The directory the demos are in
- BASE_URL: The base url that the demos can be accessed from
- BACKEND: The name for the backend to set the demos to
- KEY: The edit key for the api

The script will look in a .env file if the variables aren't set in the environment
