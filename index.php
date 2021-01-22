<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <title>VPS-Status</title>
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link rel="stylesheet" href="normalize.css">
    <link rel="stylesheet" href="sakura.css">
    <style>
        body {
            background-image: linear-gradient(135deg, #1b8479 10%, #2f2026 100%);
        }
        .home {
            padding: 1em;
            margin-top: 3em;
            background-color: rgba(255, 255, 255, 0.8);
        }
    </style>
</head>
<body>
    <div class="home">
        <h1>VPS-Status</h1>
        <?php
            //PHP获取磁盘大小
            function get_disk_total(int $total) : string
            {
                $config = [
                    '3' => 'GB',
                    '2' => 'MB',
                    '1' => 'KB'
                ];
                foreach($config as $key => $value){
                    if($total > pow(1024, $key)){
                        return round($total / pow(1024,$key)).$value;
                    }
                    return $total . 'B';
                }
            }
            //PHP获取CPU占有率
            function get_cpufree(){
                $cmd =  "top -n 1 -b -d 0.1 | grep 'Cpu'";//调用top命令和grep命令
                $lastline = exec($cmd,$output);
                preg_match('/(\d+)\.(\d+)\s+us/',$lastline, $matches);//正则表达式获取cpu空闲百分比
                $cpufree = $matches[1];
                return $cpufree;
            }
            //获取内存空闲百分比
            function get_memfree(){
                $cmd =  'free -m';//调用free命令
                $lastline = exec($cmd,$output);
                preg_match('/Mem:\s+(\d+)\s+(\d+)/',$output[1], $matches);
                $memtotal = $matches[1];
                $memuseed = $matches[2];
               // preg_match('/Swap:\s+(\d+)\s+(\d+)/',$output[2], $matches);
                $memfree = $memuseed/$memtotal;
               
                return $memfree;
            }
            //
            echo '<table><thead><tr><th align="left">磁盘总容量</th><th align="left">磁盘剩余空间</th><th align="left">CPU占有率</th><th align="left">内存占有率</th></tr></thead><tbody><tr>';
            echo '<td align="left">'.get_disk_total(disk_total_space('.')).'</td>';
            echo '<td align="left">'.get_disk_total(disk_free_space('.')).'</td>';
            echo '<td align="left">'.get_cpufree().'%</td>' ;
            echo '<td align="left">'.round(get_memfree()*100,2).'%</td>' ;
            echo '</tr></tbody></table>';
        ?>
    </div>
    <script type="text/javascript" color="255,255,255" opacity='0.7' zIndex="-2" count="200" src="canvas-nest.min.js"></script>
</body>
</html>
