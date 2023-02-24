<?php
class storeTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('store');
    }

    /**
     * Test get app detail info.
     *
     * @param  int    $id
     * @param  int    $analysis
     * @param  string $name
     * @param  string $version
     * @param  string $channel
     * @access public
     * @return Object|nulll
     */
    public function getAppInfoTest($id, $analysis = false, $name = '', $version ='',  $channel = '')
    {
        $objects = $this->objectModel->getAppInfo($id);

        // @todo finish it
        return $objects;
    }
}
