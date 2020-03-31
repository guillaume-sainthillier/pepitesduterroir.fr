TRUNCATE `PREFIX_zcolorsfonts`;
TRUNCATE `PREFIX_zcolumnblock`;
TRUNCATE `PREFIX_zcolumnblock_lang`;
TRUNCATE `PREFIX_zdropdown`;
TRUNCATE `PREFIX_zdropdown_lang`;
TRUNCATE `PREFIX_zhomeblock`;
TRUNCATE `PREFIX_zhomeblock_lang`;
TRUNCATE `PREFIX_zhometab`;
TRUNCATE `PREFIX_zhometab_lang`;
TRUNCATE `PREFIX_zmenu`;
TRUNCATE `PREFIX_zmenu_lang`;
TRUNCATE `PREFIX_zpopupnewsletter`;
TRUNCATE `PREFIX_zpopupnewsletter_lang`;
TRUNCATE `PREFIX_zproduct_extra_field`;
TRUNCATE `PREFIX_zproduct_extra_field_lang`;
TRUNCATE `PREFIX_zslideshow`;
TRUNCATE `PREFIX_zslideshow_lang`;
TRUNCATE `PREFIX_zthememanager`;
TRUNCATE `PREFIX_zthememanager_lang`;
INSERT INTO `PREFIX_zcolorsfonts` (`id_zcolorsfonts`, `id_shop`, `general`, `header`, `footer`, `content`, `product`, `fonts_import`, `fonts`, `custom_css`) VALUES (1,1,'','','','','','','','#header .header-logo .logo {\r\n  max-height: 73px;\r\n}');
INSERT INTO `PREFIX_zcolumnblock` (`id_zcolumnblock`, `id_shop`, `active`, `active_mobile`, `position`, `block_type`, `custom_class`, `product_filter`, `product_options`) VALUES (1,1,1,1,1,'blocktype_product','','products_selected','a:8:{s:5:\"limit\";s:1:\"5\";s:12:\"mobile_limit\";s:1:\"3\";s:13:\"enable_slider\";s:0:\"\";s:20:\"mobile_enable_slider\";s:0:\"\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"product_thumb\";s:4:\"left\";s:17:\"selected_products\";a:5:{i:0;s:1:\"1\";i:1;s:1:\"2\";i:2;s:1:\"3\";i:3;s:1:\"4\";i:4;s:1:\"5\";}s:17:\"selected_category\";b:0;}'),(2,1,1,0,2,'blocktype_html','no-box','products_featured','a:8:{s:5:\"limit\";s:1:\"3\";s:12:\"mobile_limit\";s:1:\"3\";s:13:\"enable_slider\";s:0:\"\";s:20:\"mobile_enable_slider\";s:0:\"\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"product_thumb\";s:3:\"top\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";b:0;}'),(3,1,1,0,3,'blocktype_product','','products_special','a:8:{s:5:\"limit\";s:1:\"3\";s:12:\"mobile_limit\";s:1:\"3\";s:13:\"enable_slider\";s:1:\"1\";s:20:\"mobile_enable_slider\";s:1:\"1\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"product_thumb\";s:3:\"top\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";b:0;}'),(4,1,1,1,4,'blocktype_html','shown-index','products_featured','a:8:{s:5:\"limit\";s:1:\"3\";s:12:\"mobile_limit\";s:1:\"3\";s:13:\"enable_slider\";s:0:\"\";s:20:\"mobile_enable_slider\";s:0:\"\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"product_thumb\";s:3:\"top\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";b:0;}');
INSERT INTO `PREFIX_zdropdown` (`id_zdropdown`, `id_zmenu`, `active`, `position`, `column`, `custom_class`, `content_type`, `categories`, `products`, `manufacturers`, `category_options`) VALUES (1,2,1,1,2,'category-horizontally','category','a:1:{i:0;s:1:\"2\";}','a:0:{}','a:0:{}','a:3:{s:5:\"image\";i:0;s:4:\"icon\";i:1;s:13:\"subcategories\";i:1;}'),(2,3,1,1,3,'','category','a:6:{i:0;s:1:\"3\";i:1;s:2:\"12\";i:2;s:2:\"15\";i:3;s:2:\"16\";i:4;s:2:\"17\";i:5;s:2:\"18\";}','a:0:{}','a:0:{}','a:3:{s:5:\"image\";i:1;s:4:\"icon\";i:0;s:13:\"subcategories\";i:0;}'),(4,4,1,1,3,'','category','a:3:{i:0;s:2:\"16\";i:1;s:2:\"17\";i:2;s:2:\"19\";}','a:0:{}','a:0:{}','a:3:{s:5:\"image\";i:0;s:4:\"icon\";i:0;s:13:\"subcategories\";i:1;}'),(6,5,1,1,4,'','product','a:0:{}','a:4:{i:0;s:1:\"2\";i:1;s:1:\"3\";i:2;s:1:\"4\";i:3;s:1:\"1\";}','a:0:{}',NULL),(7,6,1,1,3,'','manufacturer','a:0:{}','a:0:{}','a:6:{i:0;s:1:\"2\";i:1;s:1:\"3\";i:2;s:1:\"4\";i:3;s:1:\"5\";i:4;s:1:\"1\";i:5;s:1:\"6\";}',NULL),(8,7,1,1,2,'category-horizontally','category','a:2:{i:0;s:2:\"21\";i:1;s:2:\"22\";}','a:0:{}','a:0:{}','a:3:{s:5:\"image\";i:0;s:4:\"icon\";i:1;s:13:\"subcategories\";i:1;}'),(11,8,1,1,1,'','html','a:0:{}','a:0:{}','a:0:{}',NULL),(12,4,0,2,1,'','product','a:0:{}','a:2:{i:0;s:1:\"1\";i:1;s:1:\"2\";}','a:0:{}','a:3:{s:5:\"image\";i:0;s:4:\"icon\";i:0;s:13:\"subcategories\";i:1;}'),(13,9,1,1,2,'category-horizontally','category','a:2:{i:0;s:2:\"21\";i:1;s:2:\"22\";}','a:0:{}','a:0:{}',NULL);
INSERT INTO `PREFIX_zhomeblock` (`id_zhomeblock`, `id_shop`, `active`, `active_mobile`, `position`, `hook`, `block_type`, `custom_class`, `product_filter`, `product_options`) VALUES (1,1,1,1,2,'home_middle','blocktype_product','','products_category','a:9:{s:5:\"limit\";s:1:\"8\";s:12:\"mobile_limit\";s:1:\"4\";s:13:\"enable_slider\";s:0:\"\";s:20:\"mobile_enable_slider\";s:0:\"\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"number_column\";s:1:\"4\";s:10:\"sort_order\";s:20:\"product.position.asc\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";s:2:\"12\";}'),(2,1,1,1,1,'home_top','blocktype_tabs','','products_featured','a:9:{s:5:\"limit\";s:2:\"10\";s:12:\"mobile_limit\";s:2:\"10\";s:13:\"enable_slider\";s:0:\"\";s:20:\"mobile_enable_slider\";s:0:\"\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"number_column\";s:1:\"5\";s:10:\"sort_order\";s:20:\"product.position.asc\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";b:0;}'),(3,1,1,1,2,'home_top','blocktype_html','mb-4','products_featured','a:9:{s:5:\"limit\";s:2:\"10\";s:12:\"mobile_limit\";s:2:\"10\";s:13:\"enable_slider\";s:0:\"\";s:20:\"mobile_enable_slider\";s:0:\"\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"number_column\";s:1:\"5\";s:10:\"sort_order\";s:20:\"product.position.asc\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";b:0;}'),(4,1,0,0,1,'home_bottom','blocktype_html','mb-4','products_featured','a:9:{s:5:\"limit\";s:2:\"10\";s:12:\"mobile_limit\";s:2:\"10\";s:13:\"enable_slider\";s:0:\"\";s:20:\"mobile_enable_slider\";s:0:\"\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"number_column\";s:1:\"5\";s:10:\"sort_order\";s:20:\"product.position.asc\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";b:0;}'),(5,1,1,1,3,'home_top','blocktype_html','mb-4','products_featured','a:9:{s:5:\"limit\";s:2:\"10\";s:12:\"mobile_limit\";s:2:\"10\";s:13:\"enable_slider\";s:0:\"\";s:20:\"mobile_enable_slider\";s:0:\"\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"number_column\";s:1:\"5\";s:10:\"sort_order\";s:20:\"product.position.asc\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";b:0;}'),(6,1,1,1,4,'home_top','blocktype_product','','products_selected','a:9:{s:5:\"limit\";s:2:\"10\";s:12:\"mobile_limit\";s:1:\"4\";s:13:\"enable_slider\";s:0:\"\";s:20:\"mobile_enable_slider\";s:1:\"1\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"number_column\";s:1:\"2\";s:10:\"sort_order\";s:20:\"product.position.asc\";s:17:\"selected_products\";a:4:{i:0;s:1:\"2\";i:1;s:1:\"4\";i:2;s:1:\"3\";i:3;s:1:\"1\";}s:17:\"selected_category\";b:0;}'),(7,1,1,1,3,'home_middle','blocktype_html','mb-4','products_featured','a:9:{s:5:\"limit\";s:2:\"10\";s:12:\"mobile_limit\";s:2:\"10\";s:13:\"enable_slider\";s:0:\"\";s:20:\"mobile_enable_slider\";s:0:\"\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"number_column\";s:1:\"5\";s:10:\"sort_order\";s:20:\"product.position.asc\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";b:0;}'),(8,1,1,1,4,'home_middle','blocktype_product','','products_category','a:9:{s:5:\"limit\";s:1:\"8\";s:12:\"mobile_limit\";s:1:\"4\";s:13:\"enable_slider\";s:0:\"\";s:20:\"mobile_enable_slider\";s:0:\"\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"number_column\";s:1:\"4\";s:10:\"sort_order\";s:20:\"product.position.asc\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";s:2:\"16\";}'),(9,1,1,1,5,'home_middle','blocktype_html','mb-4','products_featured','a:9:{s:5:\"limit\";s:2:\"10\";s:12:\"mobile_limit\";s:2:\"10\";s:13:\"enable_slider\";s:0:\"\";s:20:\"mobile_enable_slider\";s:0:\"\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"number_column\";s:1:\"5\";s:10:\"sort_order\";s:20:\"product.position.asc\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";b:0;}'),(10,1,1,1,6,'home_middle','blocktype_product','','products_category','a:9:{s:5:\"limit\";s:1:\"8\";s:12:\"mobile_limit\";s:1:\"4\";s:13:\"enable_slider\";s:0:\"\";s:20:\"mobile_enable_slider\";s:0:\"\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"number_column\";s:1:\"4\";s:10:\"sort_order\";s:20:\"product.position.asc\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";s:2:\"22\";}');
INSERT INTO `PREFIX_zhometab` (`id_zhometab`, `id_zhomeblock`, `active`, `active_mobile`, `position`, `block_type`, `product_filter`, `product_options`) VALUES (1,2,1,1,1,'blocktype_product','products_featured','a:9:{s:5:\"limit\";s:2:\"10\";s:12:\"mobile_limit\";s:1:\"4\";s:13:\"enable_slider\";s:1:\"1\";s:20:\"mobile_enable_slider\";s:1:\"1\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"number_column\";s:1:\"5\";s:10:\"sort_order\";s:20:\"product.position.asc\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";s:1:\"3\";}'),(2,2,1,1,3,'blocktype_product','products_seller','a:9:{s:5:\"limit\";s:2:\"10\";s:12:\"mobile_limit\";s:1:\"4\";s:13:\"enable_slider\";s:1:\"1\";s:20:\"mobile_enable_slider\";s:1:\"1\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"number_column\";s:1:\"5\";s:10:\"sort_order\";s:20:\"product.position.asc\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";b:0;}'),(3,2,1,1,2,'blocktype_product','products_new','a:9:{s:5:\"limit\";s:2:\"10\";s:12:\"mobile_limit\";s:1:\"4\";s:13:\"enable_slider\";s:1:\"1\";s:20:\"mobile_enable_slider\";s:1:\"1\";s:11:\"auto_scroll\";s:0:\"\";s:13:\"number_column\";s:1:\"5\";s:10:\"sort_order\";s:21:\"product.date_add.desc\";s:17:\"selected_products\";b:0;s:17:\"selected_category\";b:0;}');
INSERT INTO `PREFIX_zmenu` (`id_zmenu`, `id_shop`, `active`, `position`, `title_image`, `link_newtab`, `label_color`, `drop_column`, `custom_class`, `drop_bgcolor`, `drop_bgimage`, `bgimage_position`, `position_x`, `position_y`) VALUES (1,1,0,1,NULL,0,'#e95144',0,NULL,'#ffffff','','right bottom',0,0),(2,1,1,2,'',0,'#e95144',2,'','#ffffff','','right bottom',0,0),(3,1,1,3,'3.png',0,'#e95144',3,'','#f7f7f7','','right bottom',0,0),(4,1,1,4,'',0,'#139fbd',3,'','#ffffff','','right bottom',0,0),(5,1,1,6,NULL,0,'#e95144',4,NULL,'#ffffff','','right bottom',0,0),(6,1,1,7,NULL,0,'#e95144',3,NULL,'#ffffff','','right bottom',0,0),(7,1,1,5,NULL,0,'',3,NULL,'#ffffff','a.jpg','right bottom',0,0),(8,1,1,8,NULL,0,'#e95144',1,NULL,'#ffffff','','right bottom',0,0),(9,1,0,9,NULL,0,'#e95144',2,NULL,'#ffffff','','right bottom',0,0);
INSERT INTO `PREFIX_zpopupnewsletter` (`id_zpopupnewsletter`, `id_shop`, `active`, `width`, `height`, `bg_color`, `bg_image`, `cookie_time`, `subscribe_form`, `save_time`) VALUES (1,1,1,670,500,'#b3dad3','zonepopupnewsletter-default-background.jpg',0,1,1569296455);
INSERT INTO `PREFIX_zproduct_extra_field` (`id_zproduct_extra_field`, `id_shop`, `popup_width`, `title_image`, `hook`, `popup`, `scope`, `categories`, `products`, `manufacturers`, `position`, `custom_class`, `active`) VALUES (1,1,'560','','ProductExtraContent',0,'All Products','','','',1,'',1),(2,1,'560','','ProductExtraContent',0,'All Products','','','',2,'',1),(3,1,'560','energy-guide-icon.jpg','ProductAdditionalInfo',1,'All Products','','','',3,'',1),(4,1,'560','video-icon.jpg','ProductAdditionalInfo',1,'All Products','','','',4,'',1),(5,1,'560','','ProductAdditionalInfo',0,'All Products','','','',5,'',1),(6,1,'560','','ProductAdditionalInfo',0,'All Products','','','',6,'',1),(7,1,'560','','AfterProductThumbs',0,'All Products','','','',7,'',1);
INSERT INTO `PREFIX_zslideshow` (`id_zslideshow`, `id_shop`, `active`, `active_mobile`, `position`, `image`, `related_products`) VALUES (1,1,1,1,1,'1553750751.jpg','a:2:{i:0;s:1:\"2\";i:1;s:1:\"5\";}'),(2,1,1,1,2,'1553750771.jpg','');
INSERT INTO `PREFIX_zthememanager` (`id_zthememanager`, `id_shop`, `general_settings`, `category_settings`, `product_settings`, `svg_logo`, `header_top_bg_color`, `footer_cms_links`, `header_save_date`) VALUES (1,1,'a:15:{s:6:\"layout\";s:4:\"wide\";s:14:\"boxed_bg_color\";s:7:\"#bdbdbd\";s:12:\"boxed_bg_img\";s:17:\"zone-boxed-bg.jpg\";s:18:\"boxed_bg_img_style\";s:7:\"stretch\";s:8:\"svg_logo\";s:14:\"1565241823.svg\";s:9:\"svg_width\";i:150;s:12:\"lazy_loading\";i:1;s:11:\"sticky_menu\";i:1;s:13:\"sticky_mobile\";i:1;s:9:\"quickview\";i:1;s:10:\"scroll_top\";i:1;s:12:\"progress_bar\";i:1;s:12:\"sidebar_cart\";i:1;s:18:\"sidebar_navigation\";i:1;s:11:\"mobile_menu\";s:8:\"megamenu\";}','a:7:{s:10:\"show_image\";i:1;s:16:\"show_description\";i:1;s:18:\"expand_description\";i:1;s:18:\"show_subcategories\";i:1;s:20:\"product_grid_columns\";i:3;s:15:\"buy_in_new_line\";i:0;s:20:\"default_product_view\";s:4:\"grid\";}','a:9:{s:19:\"product_info_layout\";s:4:\"tabs\";s:26:\"product_add_to_cart_layout\";s:6:\"inline\";s:24:\"product_actions_position\";b:0;s:18:\"product_image_zoom\";i:1;s:17:\"product_countdown\";i:1;s:25:\"product_attributes_layout\";s:7:\"default\";s:17:\"combination_price\";i:1;s:20:\"combination_quantity\";i:1;s:21:\"combination_separator\";s:2:\", \";}','kosmart_logo_pilnas.svg','#f9f2e8','4,stores,prices-drop,new-products,best-sales,contact,sitemap',1570607406);
INSERT INTO `PREFIX_zcolumnblock_lang` (`id_zcolumnblock`, `id_lang`, `title`, `static_html`) VALUES (1,1,'Trending',''),(2,1,'Banner','<div class=\"banner\"><a href=\"#\"><img src=\"BASE_URLimg/cms/b7.jpg\" alt=\"b7.jpg\" width=\"270\" height=\"350\" /></a></div>'),(3,1,'Special Products',''),(4,1,'Our Stores','<h4 class=\"column-title\">Our Stores</h4>\r\n<div class=\"content\">\r\n<p><img src=\"BASE_URLimg/cms/store.jpg\" alt=\"store\" width=\"238\" height=\"135\" /></p>\r\n<p class=\"mb-0\"><a class=\"btn btn-primary\" href=\"#\">Discover Our Store <i class=\"material-icons\">trending_flat</i></a></p>\r\n</div>');
INSERT INTO `PREFIX_zdropdown_lang` (`id_zdropdown`, `id_lang`, `static_content`) VALUES (1,1,''),(2,1,''),(4,1,''),(6,1,''),(7,1,''),(8,1,''),(11,1,'<ul class=\"linklist mb-0\"><li><a href=\"#\">Theme Features</a></li>\n<li><a href=\"#\">Typography</a></li>\n<li><a href=\"#\">Banner Effect</a></li>\n<li><a href=\"#\">Buy This Theme</a></li>\n<li><a href=\"#\">Documentation</a></li>\n<li><a href=\"#\">Support</a></li>\n<li><a href=\"#\">Rating</a></li>\n</ul>'),(12,1,''),(13,1,''),(14,1,'');
INSERT INTO `PREFIX_zhomeblock_lang` (`id_zhomeblock`, `id_lang`, `title`, `static_html`) VALUES (1,1,'Mobiles & Tablets',''),(2,1,'Product Tabs',''),(3,1,'3 Banners','<div class=\"row\">\r\n<div class=\"col-12 col-md-4\">\r\n<div class=\"banner1\"><a href=\"#\"><img src=\"BASE_URLimg/cms/b6.jpg\" alt=\"b6.jpg\" width=\"370\" height=\"240\" /><span>The best new design</span> <span class=\"btn btn-primary\">Shop Now</span> </a></div>\r\n</div>\r\n<div class=\"col-12 col-md-4\">\r\n<div class=\"banner1\"><a href=\"#\"><img src=\"BASE_URLimg/cms/b6.jpg\" alt=\"b6.jpg\" width=\"370\" height=\"240\" /><span>The best new design</span> <span class=\"btn btn-primary\">Shop Now</span> </a></div>\r\n</div>\r\n<div class=\"col-12 col-md-4\">\r\n<div class=\"banner1\"><a href=\"#\"><img src=\"BASE_URLimg/cms/b6.jpg\" alt=\"b6.jpg\" width=\"370\" height=\"240\" /><span>The best new design</span> <span class=\"btn btn-primary\">Shop Now</span> </a></div>\r\n</div>\r\n</div>'),(4,1,'Parallax Banner','<div class=\"banner4\" style=\"height:500px;\">\n<div class=\"background\" style=\"background-image:url(&quot;BASE_URLimg/cms/b5.jpg&quot;);\"></div>\n<div class=\"outer\">\n<div class=\"content\">\n<h2>Look to love now</h2>\n<p>Nam vehicula, velit quis condimentum interdum, dui dui lacinia lectus, nec euismod ipsum ipsum quis urna.<br />Vestibulum ac ante at urna maximus commodo.</p>\n<p><a class=\"btn btn-primary\" href=\"#\">See more  <i class=\"caret-right\"></i></a></p>\n</div>\n</div>\n</div>'),(5,1,'Store Features','<div class=\"row\">\n<div class=\"col-12 col-sm-6 col-md-3\">\n<div class=\"feature\"><span class=\"fa fa-paper-plane\"></span> <a href=\"#\">WE SHIP TO<br />50+ COUNTRIES</a></div>\n</div>\n<div class=\"col-12 col-sm-6 col-md-3\">\n<div class=\"feature2\"><span class=\"fa fa-retweet\"></span> <a href=\"#\">30 DAYS<br />MONEY BACK GUARANTEE</a></div>\n</div>\n<div class=\"col-12 col-sm-6 col-md-3\">\n<div class=\"feature\"><span class=\"fa fa-gift\"></span> <a href=\"#\">FREE GIFT CODE<br />EVERY WEDNESDAYS</a></div>\n</div>\n<div class=\"col-12 col-sm-6 col-md-3\">\n<div class=\"feature2\"><span class=\"fa fa-clock-o\"></span> <a href=\"#\">DAILY DEALS<br />&amp; LIGHTNING DEALS</a></div>\n</div>\n</div>'),(6,1,'Today\'s Deals',''),(7,1,'2 Banners','<div class=\"row\">\r\n<div class=\"col-12 col-lg-6\">\r\n<div class=\"banner2\"><a href=\"#\"><img src=\"BASE_URLimg/cms/b4.jpg\" alt=\"b4.jpg\" width=\"570\" height=\"224\" /></a></div>\r\n</div>\r\n<div class=\"col-12 col-lg-6\">\r\n<div class=\"banner2\"><a href=\"#\"><img src=\"BASE_URLimg/cms/b4.jpg\" alt=\"b4.jpg\" width=\"570\" height=\"224\" /></a></div>\r\n</div>\r\n</div>'),(8,1,'Fashion & Accessories',''),(9,1,'Full Banner','<div class=\"row\">\r\n<div class=\"col\">\r\n<div class=\"banner2\"><a href=\"#\"><img src=\"BASE_URLimg/cms/b8.jpg\" alt=\"b8.jpg\" width=\"870\" height=\"165\" /></a></div>\r\n</div>\r\n</div>'),(10,1,'Home Appliances','');
INSERT INTO `PREFIX_zhometab_lang` (`id_zhometab`, `id_lang`, `title`, `static_html`) VALUES (1,1,'Featured Products',''),(2,1,'Best Seller Products',''),(3,1,'New Products','');
INSERT INTO `PREFIX_zmenu_lang` (`id_zmenu`, `id_lang`, `name`, `link`, `label`) VALUES (1,1,'<i class=\"material-icons\">home</i> Home','#page=home',''),(2,1,'Shop','#page=home',''),(3,1,'Electronic','#category=3',''),(4,1,'Fashion','#category=16','NEW'),(5,1,'Sales','#page=prices-drop',''),(6,1,'Brands','#page=manufacturer',''),(7,1,'Appliances','#category=22',''),(8,1,'Features','','PRO'),(9,1,'Appliances','','');
INSERT INTO `PREFIX_zpopupnewsletter_lang` (`id_zpopupnewsletter`, `id_lang`, `content`) VALUES (1,1,'<div style=\"font-size: 25px; line-height: 25px; margin-top: 20px;\">\r\n<p>SIGN UP FOR</p>\r\n<p>OUR NEWSLETTER</p>\r\n<p>& PROMOTIONS !</p>\r\n</div>\r\n<p style=\"font-size: 40px; height: 40px; line-height: 40px;\">GET</p>\r\n<p style=\"font-size: 60px; color: #e52e04; height: 60px; line-height: 60px;\">25%<span style=\"padding-left: 15px; font-size: 60%;\">OFF</span></p>\r\n<p><strong>ON YOUR NEXT PURCHASE</strong></p>');
INSERT INTO `PREFIX_zproduct_extra_field_lang` (`id_zproduct_extra_field`, `id_lang`, `title`, `content`) VALUES (1,1,'Shipping & Returns','<h5>Returns Policy</h5>\r\n<p>You may return most new, unopened items within 30 days of delivery for a full refund. We\'ll also pay the return shipping costs if the return is a result of our error (you received an incorrect or defective item, etc.).</p>\r\n<p>You should expect to receive your refund within four weeks of giving your package to the return shipper, however, in many cases you will receive a refund more quickly. This time period includes the transit time for us to receive your return from the shipper (5 to 10 business days), the time it takes us to process your return once we receive it (3 to 5 business days), and the time it takes your bank to process our refund request (5 to 10 business days).</p>\r\n<p>If you need to return an item, simply login to your account, view the order using the \'Complete Orders\' link under the My Account menu and click the Return Item(s) button. We\'ll notify you via e-mail of your refund once we\'ve received and processed the returned item.</p>\r\n<h5>Shipping</h5>\r\n<p>We can ship to virtually any address in the world. Note that there are restrictions on some products, and some products cannot be shipped to international destinations.</p>\r\n<p>When you place an order, we will estimate shipping and delivery dates for you based on the availability of your items and the shipping options you choose. Depending on the shipping provider you choose, shipping date estimates may appear on the shipping quotes page.</p>\r\n<p>Please also note that the shipping rates for many items we sell are weight-based. The weight of any such item can be found on its detail page. To reflect the policies of the shipping companies we use, all weights will be rounded up to the next full pound.</p>'),(2,1,'Customers Love Us','<ul><li>\"Excellent products and excellent service.\" - Josephine</li>\n<li>\"Excellent value. High quality products at reasonable prices.\" - Warren</li>\n<li>\"Outstanding customer service.\" - Jack</li>\n<li>\"You\'re the best! Thank you!\" - Tatiana</li>\n</ul>'),(3,1,'PRODUCT ENERGY GUIDE','<p><img src=\"BASE_URLimg/cms/energy-guide-label.jpg\" alt=\"\" width=\"750\" height=\"750\" /></p>'),(4,1,'PRODUCT VIDEO','<p><iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/S1vFssuiVu0?rel=0&controls=0&showinfo=0\"></iframe></p>\r\n<p style=\"font-weight: bold; text-align: center; margin-bottom: 0; text-transform: uppercase;\">PrestaShop • ZOne - Supermarket Theme • Installation</p>'),(5,1,'Free Shipping','<p><i class=\"material-icons\">local_shipping</i> FREE shipping on orders over <span style=\"color:#d0121a;\">$25.00</span> shipped by Amazon. <a href=\"#\">Details</a></p>'),(6,1,'In-Store Advertising','<p style=\"text-align:center;\"><img src=\"BASE_URLimg/cms/ad.jpg\" alt=\"\" width=\"230\" height=\"300\" /></p>\n<p style=\"text-align:right;font-style:italic;font-size:80%;margin-top:-6px;color:#aeaeae;\">In-Store Advertising</p>'),(7,1,'Advertising 2','<p style=\"text-align:center;\"><img src=\"BASE_URLimg/cms/ad2.jpg\" alt=\"\" width=\"290\" height=\"120\" /></p>\n<p style=\"text-align:right;font-style:italic;font-size:80%;margin-top:-6px;color:#aeaeae;\">Advertising</p>');
INSERT INTO `PREFIX_zslideshow_lang` (`id_zslideshow`, `id_lang`, `title`, `image_name`, `slide_link`, `caption`) VALUES (1,1,'Slide 1','1553750751.jpg','','<h2>Supermarket Store</h2>\r\n<p class=\"text\">Sed justo libero, posuere eget elementum sed, auctor non ante. <br /> Fusce sagittis est ut ipsum ullamcorper laoreet.</p>\r\n<p><a class=\"btn btn-primary\" href=\"#\">SHOP NOW  <i class=\"caret-right\"></i></a></p>'),(2,1,'Slide 2','1553750771.jpg','','<h2>PrestaShop Theme</h2>\r\n<p class=\"text\">Sed justo libero, posuere eget elementum sed, auctor non ante. <br />Fusce sagittis est ut ipsum ullamcorper laoreet.</p>\r\n<p><a class=\"btn btn-primary\" href=\"#\">SHOP NOW <i class=\"caret-right\"></i></a></p>');
INSERT INTO `PREFIX_zthememanager_lang` (`id_zthememanager`, `id_lang`, `header_top`, `header_phone`, `footer_about_us`, `footer_static_links`, `footer_bottom`, `cookie_message`) VALUES (1,1,'<p><strong>FREE SHIPPING</strong> on over 100 MILLION PRODUCTS. <strong>10% OFF</strong> on ALL PRODUCTS, USE CODE: <strong>SALE10</strong>. <a href=\"#\">SHOP NOW</a>.</p>','<ul>\r\n<li><a href=\"#\"><i class=\"material-icons\">card_giftcard</i> Weekly Ads</a></li>\r\n<li><a href=\"#\"><i class=\"material-icons\">location_on</i> Store Finder</a></li>\r\n<li><a href=\"#\"><i class=\"fa fa-id-card-o\"></i> About Us</a></li>\r\n<li><a href=\"#\"><i class=\"fa fa-envelope-o\"></i> Contact Us</a></li>\r\n<li><a href=\"#\"><i class=\"material-icons\">help_outline</i> Help</a></li>\r\n</ul>','<p class=\"sm-bottom\"><a href=\"#\"><img src=\"BASE_URLimg/cms/logo-footer.jpg\" alt=\"Logo footer\" width=\"688\" height=\"70\" /></a></p>\r\n<p>Aenean dignissim ante eu purus dictum, feugiat element erat luctus. Integer scelerisque, diam nec condimentum facilisis. <a title=\"See more\" href=\"#\">[...]</a></p>\r\n<p><span class=\"fa fa-phone\"></span>Tel: <strong>+12 0987654321</strong></p>\r\n<p><span class=\"fa fa-envelope\"></span>Email: example@zonemarket.com</p>\r\n<p><span class=\"fa fa-map-marker\"></span>Address: 6 Bis Rue Meyerbeer</p>','<div class=\"row\">\r\n<div class=\"col-12 col-lg-6\">\r\n<h4>Why Choose</h4>\r\n<ul>\r\n<li><a href=\"#\">Theme Features</a></li>\r\n<li><a href=\"#\">Typography</a></li>\r\n<li><a href=\"#\">Banner Effect</a></li>\r\n<li><a href=\"#\">Buy This Theme</a></li>\r\n<li><a href=\"#\">Documentation</a></li>\r\n<li><a href=\"#\">Support</a></li>\r\n<li><a href=\"#\">Rating</a></li>\r\n</ul>\r\n</div>\r\n<div class=\"col-12 col-lg-6\">\r\n<h4>Sample Menu</h4>\r\n<ul>\r\n<li><a href=\"#\">Iris Josiah Cross</a></li>\r\n<li><a href=\"#\">Wild Beam</a></li>\r\n<li><a href=\"#\">Wooden Crystal</a></li>\r\n<li><a href=\"#\">Trey Mccarthy</a></li>\r\n<li><a href=\"#\">Square Scorpion</a></li>\r\n<li><a href=\"#\">Vince Mcknight</a></li>\r\n<li><a href=\"#\">Vette Roxie Morton</a></li>\r\n</ul>\r\n</div>\r\n</div>','<div class=\"row align-items-center\">\r\n<div class=\"col-12 col-lg-6\">\r\n<p class=\"text-lg-left m-0\"><img src=\"BASE_URLimg/cms/logo-icon.png\" alt=\"Logo footer\" width=\"40\" height=\"40\" /> Copyright © 2019 <strong>ZOne • Supermarket Store</strong>  |  Powered by <a href=\"#\" target=\"_blank\" rel=\"noreferrer noopener\">PrestaShop</a></p>\r\n</div>\r\n<div class=\"col-12 col-lg-6\">\r\n<p class=\"text-lg-right m-0\" style=\"padding-top: 6px;\"><img src=\"BASE_URLimg/cms/payments.png\" alt=\"Logo footer\" width=\"359\" height=\"28\" /></p>\r\n</div>\r\n</div>','<p>This site uses cookies. By continuing to browse the site you are agreeing to our use of cookies. <a href=\"#\" title=\"EU Cookie Laws\" target=\"_blank\" rel=\"noreferrer noopener\">Find out more here</a>.</p>');
