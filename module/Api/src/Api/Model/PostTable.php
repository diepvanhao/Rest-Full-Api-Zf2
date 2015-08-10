<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Api\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class PostTable {

    protected $adapter;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function fetchAll() {
        $select = new Select();
        $select->from('post');

        $statement = $this->adapter->createStatement();
        $select->prepareStatement($this->adapter, $statement);
        $driverResult = $statement->execute();

        $resultset = new ResultSet();
        $resultset->initialize($driverResult);
//        foreach($resultset as $row){
//            var_dump($row);die();
//        }
        return $resultset->toArray();
    }

    public function getTagPost($params) {
        $tags = $params['tag'];
        if (!empty($tags)) {
            $where = "name=";
            for ($i = 0; $i < count($tags); $i++) {
                if ($i != count($tags) - 1) {
                    $where.="'$tags[$i]'" . ' or name=';
                } else {
                    $where .="'$tags[$i]'";
                }
            }

//            $result = $this->adapter->query("select DISTINCT p.* from post as p join post_tag as pt on p.postId=pt.postId join tag as t on t.tagId=pt.tagId  {$where}", Adapter::QUERY_MODE_EXECUTE);
//          var_dump($result->current());die();
            $select = new Select();
            $select->quantifier('DISTINCT');
            $select->from('post', array('title', 'body', 'postId'));
            // $select->columns(array('title','body','postId'));
            $select->join('post_tag', 'post.postId=post_tag.postId');
            $select->join('tag', 'tag.tagId=post_tag.tagId');
            $select->where($where);
            //print_r($select->getSqlString());die();
            $statement = $this->adapter->createStatement();
            $select->prepareStatement($this->adapter, $statement);
            $driverResult = $statement->execute();

            $resultset = new ResultSet();
            $resultset->initialize($driverResult);
            return $resultset->toArray();
        }
    }

    public function getCountPost($params) {
        $tags = $params['tag'];
        if (!empty($tags)) {
            $where = "name=";
            for ($i = 0; $i < count($tags); $i++) {
                if ($i != count($tags) - 1) {
                    $where.="'$tags[$i]'" . ' or name=';
                } else {
                    $where .="'$tags[$i]'";
                }
            }

//            $result = $this->adapter->query("select DISTINCT p.* from post as p join post_tag as pt on p.postId=pt.postId join tag as t on t.tagId=pt.tagId  {$where}", Adapter::QUERY_MODE_EXECUTE);
//          var_dump($result->current());die();
            $select = new Select();
            //$select->quantifier('DISTINCT');
            $select->from('post', array('count(*)'));
            $select->columns(array('total' => new \Zend\Db\Sql\Expression('COUNT(*)')));
            $select->join('post_tag', 'post.postId=post_tag.postId');
            $select->join('tag', 'tag.tagId=post_tag.tagId');
            $select->where($where);

            // print_r($select->getSqlString());die();
            $statement = $this->adapter->createStatement();
            $select->prepareStatement($this->adapter, $statement);
            $driverResult = $statement->execute();

            $resultset = new ResultSet();
            $resultset->initialize($driverResult);
            return $resultset->toArray();
        }
    }

    public function getPost($id) {
        $rowset = null;
        $id = (int) $id;
        try {
            $select = new Select();

            $select->from('post')->where('postId=?' . $id);
            $statement = $this->adapter->createStatement();
            $select->prepareStatement($this->adapter, $statement);
            $driverResult = $statement->execute();
            $resultset = new ResultSet();
            $resultset->initialize($driverResult);

            foreach ($resultset as $row) {
                $rowset = $row;
            }
        } catch (\Exception $ex) {
            return FALSE;
        }
        return $rowset;
    }

    public function savePost($params) {
        $data = array(
            'body' => $params['body'],
            'title' => $params['title']
        );
        $tags = $params['tag'];
        $id = (int) $params['id'];
        if ($id == 0) {
            $sql = new Sql($this->adapter);
            $insert = $sql->insert('post');
            $insert->values($data);
            $selectString = $sql->getSqlStringForSqlObject($insert);
            $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
            $postId = $this->adapter->getDriver()->getLastGeneratedValue();
            if (!empty($tags)) {
                //insert tag,return tag id
                foreach ($tags as $tag) {
                    if (!empty($tag)) {
                        if ($tagId = $this->checkTagExist($tag)) {
                            //update tag for post
                            $sql = new Sql($this->adapter);
                            $insert = $sql->insert('post_tag');
                            $insert->values(array('postId' => $postId, 'tagId' => $tagId));
                            $selectString = $sql->getSqlStringForSqlObject($insert);
                            $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
                        } else {
                            //insert tag and update tag for post
                            //1.insert tag
                            $sql = new Sql($this->adapter);
                            $insert = $sql->insert('tag');
                            $insert->values(array('name' => $tag));
                            $selectString = $sql->getSqlStringForSqlObject($insert);
                            $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
                            $tagId = $this->adapter->getDriver()->getLastGeneratedValue();
                            //2.update post
                            $sql = new Sql($this->adapter);
                            $insert = $sql->insert('post_tag');
                            $insert->values(array('postId' => $postId, 'tagId' => $tagId));
                            $selectString = $sql->getSqlStringForSqlObject($insert);
                            $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
                        }
                    }
                }
            }


            return array('id' => $postId);
        } else {
            if ($this->getPost($id)) {
                $sql = new Sql($this->adapter);
                $update = $sql->update();
                $update->table('post');
                $update->set($data);
                $update->where(array('postId' => $id));
                $statement = $sql->prepareStatementForSqlObject($update);
                $results = $statement->execute();
                return array('id' => $id);
            } else {
                throw new \Exception('Post id does not exist');
            }
        }
    }

    public function saveLog($post) {

        $data = array(
            'body' => $post->body,
            'title' => $post->title,
            'postId' => $post->postId
        );
        $sql = new Sql($this->adapter);
        $insert = $sql->insert('log');
        $insert->values($data);
        $selectString = $sql->getSqlStringForSqlObject($insert);

        $results = $this->adapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        return $results;
    }

    public function deletePost($id) {
        $sql = new Sql($this->adapter);
        $delete = $sql->delete('post')->where(array("postId" => $id));

        //see the deleted entry    
        $deleteString = $sql->prepareStatementForSqlObject($delete);
        $results = $deleteString->execute();
        //delete post in post tap
        $delPostTag = $sql->delete('post_tag')->where(array("postId" => $id));

        //see the deleted entry    
        $deleteString = $sql->prepareStatementForSqlObject($delPostTag);
        $results = $deleteString->execute();
        return array('id' => $id);
        // $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function checkTagExist($tag) {
        $rowset = null;
        $tagname = trim($tag);
        try {
//            $select = new Select();
//
//            $select->from('tag')->where('name=?'."'$tagname'");
//            
//            $statement = $this->adapter->createStatement();
//            $select->prepareStatement($this->adapter, $statement);
//            $driverResult = $statement->execute(); 
//            $resultset = new ResultSet();
//            $resultset->initialize($driverResult);
//            
//            foreach ($resultset as $row) {
//                var_dump($row);die();
//            }
            $result = $this->adapter->query("select * from tag where name='{$tagname}'", Adapter::QUERY_MODE_EXECUTE);
            $s = $result->current();

            return $s->tagId;
        } catch (\Exception $ex) {
            return FALSE;
        }
    }

}
