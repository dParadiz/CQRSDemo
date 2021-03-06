<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 18.5.2015
 * Time: 23:22
 */

namespace App\Data;


use CQRSDemo\Common\StorageInterface;

/**
 * Class Storage
 * @package Data
 */
class Storage implements StorageInterface
{

    protected $storageDir;

    public function __construct($storageDir)
    {
        $this->storageDir = $storageDir;
    }


    public function getStoredContent()
    {
        $dataDir = $this->getDataDir();
        if (!file_exists($dataDir . '/stored_data.json')) {
            touch($dataDir . '/stored_data.json');
        }
        $content = file_get_contents($dataDir . '/stored_data.json');
        $content = json_decode($content, true);

        return $content;
    }

    public function storeContent($content)
    {
        $dataDir = $this->getDataDir();
        touch($dataDir . '/stored_data.json');
        file_put_contents($dataDir . '/stored_data.json', json_encode($content));
    }

    /**
     * @param $id
     * @return null
     */
    public function getData($id)
    {
        $content = $this->getStoredContent();
        if (isset($content[$id])) {
            return $content[$id];
        }

        return null;
    }

    /**
     * @param int $id
     */
    public function removeData($id)
    {
        $content = $this->getStoredContent();
        if (isset($content[$id])) {
            unset($content[$id]);
        }
        self::storeContent($content);
    }

    /**
     * @param array $id
     * @param array $data
     * @return int
     */
    public function addData($id, $data)
    {
        $content = $this->getStoredContent();
        $content[$id] = $data;
        self::storeContent($content);
    }

    /**
     * @return string
     */
    private function getDataDir()
    {
        return $this->storageDir;
    }
}