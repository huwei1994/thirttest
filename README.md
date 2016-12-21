
1：在laravel系统的config的filesystems.php 文件中的 disk数组中，增加如下两个数组
'scms_biguploads' => 
[
  'driver' => 'local',
  'root' => public_path('assets/scms/image/uploads/bigimg'),
  'visibility' => 'public',
],
'scms_smalluploads' => 
[
  'driver' => 'local',
  'root' => public_path('assets/scms/image/uploads/smallimg'),
  'visibility' => 'public',
],

2：现在的所有依赖，要手动加入到你的系统中，
