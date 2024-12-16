<?php

namespace App\Library\Traits;

trait DirectoryHelper
{
    /**
     * 转化 \ 为 /
     * 
     * @param string $path 路径
     * @return string 路径
     */
    public function dirPath($path)
    {
        return rtrim(str_replace('\\', '/', $path), '/') . '/';
    }

    /**
     * 创建目录及其父目录
     * 
     * @param string $path 路径
     * @param string $mode 属性
     * @return bool 如果已经存在则返回true，否则为false
     */
    public function dirCreate($path, $mode = 0777)
    {
        $path = $this->dirPath($path);
        if (is_dir($path)) {
            return true; // 目录已存在
        }

        // 创建目录的父级路径
        $temp = explode('/', $path);
        $cur_dir = '';
        foreach ($temp as $segment) {
            $cur_dir .= $segment . '/';
            if (!is_dir($cur_dir)) {
                // 使用 umask 配置更准确的权限
                mkdir($cur_dir, $mode, true);
                chmod($cur_dir, $mode);
            }
        }

        return true;
    }

    /**
     * 拷贝目录及其所有文件
     * 
     * @param string $fromdir 原路径
     * @param string $todir 目标路径
     * @return bool 如果目标路径不存在则返回false，否则为true
     */
    public function dirCopy($fromdir, $todir)
    {
        $fromdir = $this->dirPath($fromdir);
        $todir = $this->dirPath($todir);

        if (!is_dir($fromdir)) {
            return false; // 原目录不存在
        }

        // 如果目标目录不存在，先创建
        if (!is_dir($todir)) {
            $this->dirCreate($todir);
        }

        // 获取源目录中的文件/子目录
        $list = scandir($fromdir);
        foreach ($list as $file) {
            if ($file == '.' || $file == '..') continue;
            $src = $fromdir . $file;
            $dest = $todir . $file;
            if (is_dir($src)) {
                $this->dirCopy($src, $dest); // 递归复制子目录
            } else {
                copy($src, $dest);
                chmod($dest, 0777); // 设置文件权限
            }
        }

        return true;
    }

    /**
     * 转换目录下所有文件编码格式
     * 
     * @param string $in_charset 原字符集
     * @param string $out_charset 目标字符集
     * @param string $dir 目录地址
     * @param string $fileexts 转换的文件格式
     * @return bool 如果字符集相同则返回false，否则返回true
     */
    public function dirIconv($in_charset, $out_charset, $dir, $fileexts = 'php|html|htm|js|txt|xml')
    {
        if ($in_charset === $out_charset) return false; // 如果字符集相同，不进行转换

        // 避免多次扫描目录，提前获取所有匹配文件
        $files = $this->dirList($dir, $fileexts);
        foreach ($files as $file) {
            $content = file_get_contents($file);
            file_put_contents($file, iconv($in_charset, $out_charset, $content));
        }

        return true;
    }

    /**
     * 列出目录下所有文件
     * 
     * @param string $path 路径
     * @param string $exts 扩展名
     * @return array 所有满足条件的文件
     */
    public function dirList($path, $exts = '')
    {
        $path = $this->dirPath($path);
        $files = [];
        
        // 使用 scandir 替代 glob，减少内存使用
        $list = scandir($path);
        foreach ($list as $file) {
            if ($file == '.' || $file == '..') continue;
            $fullPath = $path . $file;
            if (is_file($fullPath)) {
                if (!$exts || pathinfo($file, PATHINFO_EXTENSION) == $exts) {
                    $files[] = $fullPath;
                }
            } elseif (is_dir($fullPath)) {
                $files = array_merge($files, $this->dirList($fullPath, $exts)); // 递归查找子目录
            }
        }

        return $files;
    }

    /**
     * 设置目录下所有文件的访问和修改时间
     * 
     * @param string $path 路径
     * @param int $mtime 修改时间
     * @param int $atime 访问时间
     * @return bool 如果不是目录，则返回false；否则返回true
     */
    public function dirTouch($path, $mtime = null, $atime = null)
    {
        if (!is_dir($path)) {
            return false; // 不是目录
        }

        $path = $this->dirPath($path);
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;
            $fullPath = $path . $file;
            is_dir($fullPath) ? $this->dirTouch($fullPath, $mtime, $atime) : touch($fullPath, $mtime, $atime);
        }

        return true;
    }

    /**
     * 获取目录树
     * 
     * @param string $dir 路径
     * @param int $parentid 父id
     * @param array $dirs 传入的目录
     * @return array 返回目录列表
     */
    public function dirTree($dir, $parentid = 0, $dirs = [])
    {
        $list = scandir($dir);
        foreach ($list as $file) {
            if ($file == '.' || $file == '..') continue;
            $fullPath = $dir . $file;
            if (is_dir($fullPath)) {
                $dirs[] = ['id' => count($dirs), 'parentid' => $parentid, 'name' => basename($fullPath), 'dir' => $fullPath . '/'];
                $dirs = $this->dirTree($fullPath . '/', count($dirs) - 1, $dirs);
            }
        }

        return $dirs;
    }

    /**
     * 删除目录及目录下所有文件
     * 
     * @param string $dir 路径
     * @return bool 如果成功则返回TRUE，失败返回FALSE
     */
    public function dirDelete($dir)
    {
        $dir = $this->dirPath($dir);

        if (!is_dir($dir)) {
            return false; // 不是有效目录
        }

        // 获取目录下所有的文件和子目录
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;
            $fullPath = $dir . $file;
            is_dir($fullPath) ? $this->dirDelete($fullPath) : unlink($fullPath);
        }

        return rmdir($dir); // 删除空目录
    }

    /**
     * 检查目录是否存在
     *
     * @param string $path 路径
     * @return bool 如果目录存在返回 true，否则返回 false
     */
    public function dirExists($path)
    {
        return is_dir($path);
    }

    /**
     * 检查文件是否存在
     *
     * @param string $file 文件路径
     * @return bool 如果文件存在返回 true，否则返回 false
     */
    public function fileExists($file)
    {
        return is_file($file);
    }

    /**
     * 删除文件
     *
     * @param string $file 文件路径
     * @return bool 如果成功删除文件返回 true，否则返回 false
     */
    public function fileDelete($file)
    {
        return file_exists($file) && unlink($file);
    }
}
