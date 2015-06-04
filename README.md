Bartwit is a fork of Retwis that uses a Baratine service as the backing
storage instead of Redis.

To run Bartwit
--------------

1. build and deploy to Baratine the [Bache service](https://github.com/baratine/bache)

2. download Bartwit into your web server that supports PHP

3. configure the Baratine host in bartwit/retwis.php, where the default is http://localhost:8085/s/pod
