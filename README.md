# RequestLockBundle #

## About ##

The aim of this bundle is to prevent from accepting multiple duplicate requests e.g. multiple clicks on submit button on frontend side. At this moment this bundle has two strategies of dealing with duplicate requests:
1. Wait until previous request is finished
2. Throw exception


## How does it work ##
The bundle uses Symfony Lock component under the hood. Read more here: https://symfony.com/doc/current/components/lock.html

When request arrives it locks request in your preferred lock store. By default it's file system. So if you want to make it work on multiple servers, better use RedisStore which will be shared across multiple instances.

To mark requests as identical the bundle compares request headers, url, method and body. In some cases it may not work even requests at first sight looks the same. For example when using Varnish in front of your app, it will append X-Varnish header which contain unique request ID. To handle such cases you can specify ignored headers in bundle configuration.

GET requests will be ignored.

## Installation ##
Run `composer require rsumka/request-lock-bundle`

## Configuration ##
By default the bundle won't ignore any headers and will use `wait_for_lock_release_strategy`. After installing bundle you can create configuration file `request_lock.yaml` in config/packages with:
```yaml
request_lock:
  ignored_headers:
    - 'X-Varnish'
  request_duplicate_handling_strategy: 'throw_exception_strategy'
```

Available strategies:
- `wait_for_lock_release_strategy`
- `throw_exception_strategy`

## License ##

See [LICENSE](LICENSE).