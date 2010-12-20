Getting Started with Limonade
=============================

This introduction will make you dive into Limonade by showing you how to write a simple blog with Limonade.

You can also find the complete <a href="http://github.com/sofadesign/limonade-blog-example">final code for this
classical blog example</a> with <a href="http://www.php.net/manual/en/book.pdo.php" title="PHP: PDO - Manual">pdo</a>.

First steps: get Limonade say hello
-----------------------------------

* Create a folder in your web server document root. We'll name it `lim_blog`.
* Download [the last stable version](https://github.com/sofadesign/limonade/zipball/master) of Limonade     and unzip it in `lim_blog/vendor`. Rename the folder `limonade` if necessary.
* Create your `lim_blog/index.php`

Now we'll load `limonade` and make it say hello

    <?php
        # First we load the limonade library
        require_once 'vendor/limonade/lib/limonade.php';
        
        # Then when we call the root url path of our blog project,
        # we want Limonade to call the `hello` function
        dispatch('/', 'hello');
        
        function hello(){
            # We make limonade display Hello world
            return 'Hello world!';
        }
        
        # Now ask limonade to run
        run();

Now go to `http://localhost/lim_blog/` in your browser, you should see:

![Hello World](images/hello_world.png)


A restful structure
-------------------

( lien restful )
( limonade support conception restful mais vous pouvez utiliser un autre architecture; but there are many advantages to use a restful architecture ).


    #  HTTP Method |  Url path         |  Controller function
    # -------------+-------------------+-------------------------------------------
    #   GET        |  /posts           |  blog_posts_index
    #   GET        |  /posts/:id       |  blog_posts_show 
    #   GET        |  /posts/new       |  blog_posts_new 
    #   POST       |  /posts           |  blog_posts_create
    #   GET        |  /posts/:id/edit  |  blog_posts_edit 
    #   PUT        |  /posts/:id       |  blog_posts_update
    #   DELETE     |  /posts/:id       |  blog_posts_destroy
    #   GET        |  /                |  blog_posts_home (redirect to /posts)
    # -------------+-------------------+-------------------------------------------

    # matches GET /posts: will show the list of posts
    dispatch()
    
    # matches GET /posts/123: will the post with id 123
    
    # matches GET…


Go further
------------------

Advanced routing, controller callbacks, hooks, helpers… Now you can read [advanced documentation]() to learn about all Limonade features.


