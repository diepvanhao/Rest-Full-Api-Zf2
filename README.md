Lazada test
Rest full api

Technologies use: 

Framework: Zf2
Database: mysql
Server: Wamp
Tool test: post man of chrome advance client test 

Link download source: https://github.com/diepvanhao/Rest-Full-Api-Zf2

After download extract source code into your server, I'm assumed your server is wamp run on window.

1.Config wamp server
==>uncomment rewrite.so and vhosts in http.conf
==>set virtual host on http-vhosts.conf as follow:

<VirtualHost *:80>
	ServerName zf2.localhost
	DocumentRoot E:\wamp\www\zf2\public
	SetEnv APPLICATION_ENV "development"
	<Directory E:\wamp\www\zf2\public>
		DirectoryIndex index.php
		AllowOverride All
		Order allow,deny
		Allow from all
	</Directory>
</VirtualHost>

<VirtualHost *:80>
	ServerName localhost
	DocumentRoot E:\wamp\www
	SetEnv APPLICATION_ENV "development"
	<Directory E:\wamp\www>
		DirectoryIndex index.php
		AllowOverride All
		Order allow,deny
		Allow from all
	</Directory>
</VirtualHost>

In that: ServerName ,DocumentRoot and Directory will edit for your machine.
Open host file and edit 127.0.0.1 zf2.localhost
Now, you can access your project type: http://zf2.localhost 
If you want to upload source to your domain name, then set up virtualhost for your domain, i recommend use localhost to run this test.

2.Config connect database for source code

The first,we create database name and import db.sql file , find this file in database folder of source code.

==>open global.php in /config/autoload and find line "'dsn'            => 'mysql:dbname=zf2api;host=localhost',"
change dbname for your database name.
==>open local.php in /config/autoload and set your database account.

That's it !! start your server and run project 

A. DB Guide
We have 4 tables: post(postId,title,body),tag(tagId,name),post_tag(postId,tagId) and log(id,postId,title,body);
1. post table contains posts
2. tag table contains tags
3. post_tag table contains postId and tagId for many to many relation
4. log table contains post have deleted.

B. API Guide

1. Get all post without tag condition

Request: Url: zf2.localhost
	 Method: Get
Response: all posts with format json.

2. Add new post

Request: Url: zf2.localhost/api/add
	Method: Post
	Params: title,body,tag[] (this is option and it is a array,maybe you input 1 or more tag or 0)
Response: return id is created of post. If add new post with tags, system will get id of tag (if exist) or new insert tag, after update tag for post
	Format: json

3. Edit post

Request: Url: zf2.localhost/api/edit/$id(post id)

	Method: Post
	Params: title,body
Response: return id of post edited if success, or notify error if fail.
	Format: json
4.Get post by tag or tags

Request: Url: zf2.localhost/api/getpost
	Method: Post
	Params: tag[] (array tag)
Response: return list post by tag or tags if finded 
	Format: json
5. Count post by tag or tags

Request: Url: zf2.localhost/api/countpost
	Method: Post
	Params: tag[] (array tag)
Response: return total post  by tag or tags
	Format: json
6. Delete post

Request: Url: zf2.localhost/api/delete/$id(post id)
	Method: post
Response: return id post deleted and save into  log table or notify error if not found post id in database.
	Format: json

Note: haven't implemented post json to api yet. Just test normal post. Need to more time to optimize high load for system, because i must spend time to research zf2.