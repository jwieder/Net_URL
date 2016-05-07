# Net_URL

Resolves a dependency issue when installing HTTP_Request v1 using Composer. The original HTTP_Request is required by a PHP class 
for a widely used API. This is a quick fix until I have the time to write my own class with HTTP_Request v2 and/or curl.

Implementing this package is fairly simple, as can be seen in this example:

    {
        "repositories": [
            {
                "type": "git",
                "url": "https://github.com/jwieder/Net_URL"
            }
        ],
        "require": {
            "pear/net_url" : "dev-tags/RELEASE_2_0_0"
        }
    }

Currently I've only included a composer.json file in the following branches, since this covers all of my use cases (I will move a copy to all of the branches as time permits, or if someone would like to help it would be much appreciated): 

- RELEASE_2_0_0
- trunk
- PHP4

As per the Composer documentation, individual branches can be specified on the require field by listing the name of the branch, prepended by `dev-`, to the version field. For example, I could specify the trunk branch with the following:

        "require": {
            "pear/net_url" : "dev-tags/trunk"
        }

@jwieder

Website: https://consulting.joshwieder.net | http://joshwieder.net

Email: contact@joshwieder.net
