# App Engine for PHP 7.2 Error Reporting samples

This app demonstrates how to report errors on on App Engine for PHP 7.2.

## Setup

- Install [`composer`](https://getcomposer.org)
- Install dependencies by running:

    ```sh
    composer install
    ```

- Install the [Google Cloud SDK](https://developers.google.com/cloud/sdk/).

## Deploy

### Run Locally

You can run the sample locally using PHP's build-in web server:

```
# export environemnt variables locally which are set by app engine when deployed
export GOOGLE_CLOUD_PROJECT=YOUR_PROJECT_ID
export GAE_SERVICE=local
export GAE_VERSION=testing

# Run PHP's built-in web server
php -S localhost:8000
```

Browse to `localhost:8000` to see a list of examples to execute.

### Deploy with gcloud

Deploy the samples by doing the following:

```
gcloud config set project YOUR_PROJECT_ID
gcloud app deploy
gcloud app browse
```

The last command will open `https://{YOUR_PROJECT_ID}.appspot.com/`
in your browser. Browse to `/` to see a list of examples to execute.
