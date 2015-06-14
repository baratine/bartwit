Bartwit is a fork of [Retwis](http://redis.io/topics/twitter-clone) that uses a Baratine service as the backing
storage instead of Redis.  For a comparison between Bartwit and Retwis, see the [Bache service](https://github.com/baratine/bache).


To run Bartwit
--------------
1. build and deploy to Baratine the [Bache service](https://github.com/baratine/bache)

2. download Bartwit into your web server that supports PHP (with curl)

3. configure the Baratine host in bartwit/retwis.php, where the default is `http://localhost:8085/s/pod`


Bartwit vs Retwis Benchmark
---------------------------
For the same number of users and posts on `timeline.php`:

|         |   1 client   | 64 clients
--------- | ------------ | -------------------
| Retwis  |  1160 req/s  | 3570 req/s
| Bartwit |  1140 req/s  | 2790 req/s

Bartwit shows that for a real world application, Bache (and in turn Baratine) performs very competitively versus Redis. 
The big difference is that Bache is just Java code packaged within a jar file; you can easily extend Bache
with bespoke functionality that better suits your specific application.
