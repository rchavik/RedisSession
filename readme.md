Redis Session Data source for Cakephp 2.x - Croogo 1.5
------------------------------------------------------

A simple redis session store with extra functionality that keeps a 'map' of
logged-in users. Authenticate objects can then use this map to prevent multiple
login of the same user.

Requirement:

- wddx module is activated
- cakephp > 2.2

CakePHP
-------

Add the following in `Config/bootstrap.php`:

`CakePlugin::load('RedisSession', array('bootstrap' => true));`

Croogo 1.5
----------

Activate the plugin via the admin backend, or via CLI:

`Console/cake ext activate plugin RedisSession`
