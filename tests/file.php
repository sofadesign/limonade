<?php
if(!defined('LIMONADE')){$h="HTTP/1.0 401 Unauthorized";header($h);die($h);}// Security check

test_case("File");
   test_case_describe("Testing limonade file functions.");
   
   function before_each_test_in_file()
   {
     
   }
   
   function test_file_mime_type()
   {
      $mimes = mime_type();
      assert_true(is_array($mimes));
      assert_not_empty($mimes);
      assert_empty(mime_type(''));
      assert_null(mime_type('unknown_extension'));
      assert_equal(mime_type('txt'), 'text/plain');
      assert_equal(mime_type('TXT'), 'text/plain');
      assert_equal(mime_type('jpg'), 'image/jpeg');
      assert_equal(mime_type('JPG'), 'image/jpeg');
   }
   
   function test_file_extension()
   {
     assert_equal(file_extension('my_file'), '');
     assert_equal(file_extension('my_file.txt'), 'txt');
     assert_equal(file_extension('my_file.html.php'), 'php');
     assert_equal(file_extension('my_file.JPG'), 'JPG');
   }
   
   function test_file_mime_content_type()
   {
     assert_not_empty(file_mime_content_type(''));
     assert_not_empty(file_mime_content_type('my_file'));
     assert_equal(file_mime_content_type('my_file'), 'application/octet-stream');
     assert_equal(file_mime_content_type('my_file.txt'), 'text/plain');
     assert_equal(file_mime_content_type('my_file.TXT'), 'text/plain');
     assert_equal(file_mime_content_type('my_file.jpg'), 'image/jpeg');
     assert_equal(file_mime_content_type('my_file.JPG'), 'image/jpeg');
   }
   
   function test_file_is_text()
   {
     assert_true(file_is_text('my_file.txt'));
     assert_true(file_is_text('my_file.TXT'));
     assert_true(file_is_text('my_file.css'));
     assert_true(file_is_text('my_file.csv'));
     assert_false(file_is_text('my_file.jpg'));
   }
   
   function test_file_is_binary()
    {
      assert_false(file_is_binary('my_file.txt'));
      assert_false(file_is_binary('my_file.TXT'));
      assert_false(file_is_binary('my_file.css'));
      assert_false(file_is_binary('my_file.csv'));
      assert_true(file_is_binary('my_file.jpg'));
      assert_true(file_is_binary('my_file.swf'));
    }
   
   function test_file_path()
   {
     $p = "/one/two/three";
     assert_equal(file_path('/one','two','three'), $p);
     assert_equal(file_path('/one','/two','three'), $p);
     assert_equal(file_path('/one','two','///three'), $p);
     assert_equal(file_path('/one','two','three/'), $p.'/');
     assert_equal(file_path('/one','two','three//'), $p.'/');
     assert_equal(file_path('/one', '\two', '\\three//'), $p.'/');
   }
   
end_test_case();
