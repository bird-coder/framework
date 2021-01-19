<?php

namespace service;

class News extends Basic
{
    public function getList() {
        global $conf;

        $gameId = param('gameId');
        $opgameId = param('opgameId');
        $opId = intval(param('opId', 0));
        if (empty($gameId)) output(['status' => 1, 'msg' => '缺少游戏ID！']);
        $params = array(
            'gameId' => $gameId,
            'status' => 1
        );
        if (!empty($opgameId)) {
            $params['opgameId'] = $opgameId;
        }

        $redis = localRedisConnect();
        $redisKey = $conf['redis_key_h5activity'] . implode('_', $params).'_'.$opId;
        $ret = $redis->get($redisKey);
        if ($ret) {
            $list = json_decode($ret, true);
        } else {
            $dba = dba();
            $sql = 'select a.*,b.name from (select * from h5activity where gameId=? and status = ?';
            if (isset($params['opgameId'])) $sql .= ' and opgameId = ?';
            $sql .= ' and startTm < ? and endTm > ?) a';
            $sql .= ' inner join (select id,name from upload_images where gameId = ?) b on a.pic=b.id;';
            $time = time();
            array_push($params, $time, $time, $gameId);
            $info = $dba->select($sql, array_values($params));

            $list = [];
            if ($info) {
                foreach ($info as $ele) {
                    if ($opId > 0 && strpos($ele['opList'].',', $opId.',') === false) {
                        continue;
                    }
                    $img = $conf['cdn_url'] . '/images/' . $gameId . '_' . $ele['name'].'?v='.$ele['updateTm'];
                    $tmp = array(
                        'id' => $ele['id'],
                        'index' => $ele['index'],
                        'name' => $ele['title'],
                        'type' => $ele['type'],
                        'horizontal' => $ele['horizontal'],
                        'url' => $ele['url'],
                        'pic' => $img,
                        'level' => $ele['level'],
                        'max_level' => $ele['max_level'],
                        'vipLevel' => $ele['vipLevel'],
                        'max_vip' => $ele['max_vip'],
                    );
                    $list[] = $tmp;
                }
                unset($info, $tmp);
            }
            $redis->set($redisKey, json_encode($list));
            $redis->expire($redisKey, $conf['redis_key_list_expire']); //缓存5分钟
        }

        output(['status' => 0, 'msg' => '成功', 'list' => $list]);
    }
}
