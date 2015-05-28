<?php
/**
 * Created by PhpStorm.
 * User: dejan
 * Date: 19.5.2015
 * Time: 20:14
 */

namespace CQRSDemo\Common;

/**
 * Interface StorageInterface
 * @package CQRSDemo\Common
 */
interface StorageInterface
{
    /**
     * @return array
     */
    public function getStoredContent();


    public function storeContent($content);

    /**
     * @param $id
     * @return array|null
     */
    public function getData($id);

    /**
     * @param int $id
     */
    public function removeData($id);

    /**
     * @param $id
     * @param array $data
     */
    public function addData($id, $data);
}