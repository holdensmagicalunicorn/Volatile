seiteki - a small, static, quick blog engine
============================================

seiteki means static in japanese and it's exactly what this engine 
intend to do. Create a static version of your blog.

seiteki is a blog engine built with the power users in mind,
althought anyone should be able to use it.

It is built to be quick and small. 
It's for people who :
    - don't want to spend their time upgrading WordPress
    - don't want to take care of dozens of plugins 
    - don't like/use categories or tags
    - want to focus on writing good stuff
    - have a small and quick blog engine that you can run anywhere
    
Requirements
------------
    - Apache 2.0 or superior
    - PHP 5.2 or superior
    - grep (seiteki use it for searching throught your posts)
    - That's it.
If you can't have this with your hosting company : change as quickly as 
possible because they suck.

What's the idea
---------------
The idea is to use plain text file and a PHP layout.
We create HTML file from theses two worlds.

An html page is created by assembling theses contents:
    - _layouts/header.php
    - _posts/Y-M-D-title.md
    - _layouts/sidebar.php
    - _layouts/footer.php

Seiteki will take care for you of a few things :
    - create and maintain an up-to-date cache with only html pages
    - redirect users with cool permalink structure (per year, year/month, year/month/day and title)
    - create an archive page with all your posts
    - search thought your posts
    - create a valid atom rss feed
    
How to use 
----------
    Checkout this repo where your want your blog to be on your server :
    $ cd /var/www
    $ git clone https://github.com/maximeh/seiteki blog

Then READ the config.php and tune the variable as you want them.

If you already have a wordpress install, take a look at _inc/frow_wp/
there is a script called convert_from_wordpress.php, open it and fullfill
the hosts/users/password variables.

This will create a file for each article in your wordpress installation, 
you can find in the _posts folder.
Note the way the file are named, it's always Y-M-D-title.md, when you write 
your new articles, they should have a name like that, where :
    - Y is the current year
    - M is the current month
    - D is the current day
    - title, well, its the title of your article

You may have noticed the strange extension for you articles : ".md"
It's the extension for the markdown format created and defined [here](http://daringfireball.net/projects/markdown/ "Markdown Project")

Since your posts are files, you can write them anywhere, where you want 
with your software of choice.
And NO, there will never be any TinyMCE of any kind here nor there will
be any admin panel.
    
Layout
------
The folder layout contains your design, in this folder seiteki NEED to find 
three files :
    - header.php
    - sidebar.php
    - footer.php
If you don't use a footer or a sidebar, just create empty file.

In layout folder, you can do whatever you want. Do as many dynamic thing as you
want.
In my _layouts, I have an Ajax search, a contact form and a flickr class that 
I use to get random image from my flick account.

Since the search in handled by seiteki, you'll get only the results, you can 
obviously style it using your css.
As the archive are also generated by the engine, you can style it too within your CSS.
    
Extra
-----
If you want, you may setup some hooks for git, so when you push your posts, 
your blog is automatically updated.

    $ echo 'php update.php' > .git/hooks/post-commit
    $ chmod 755 .git/hooks/post-commit

If the comments are something important for you, I really recommend you to use disqus, it 
will add a little overhead to your HTML but not that much. A built-in support for disqus will
be implemented soon.
Note : You can import wordpress comment into disqus, look in their pages.

TODO
----
Here is a list of what I thought could be feature "nice to have" :
    - really maybe : create the sitemap.xml to help google (http://www.smart-it-consulting.com/article.htm?node=133&page=37)
    - add a DISQUS support built-in, check wordpress import
    
