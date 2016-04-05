<?php

class Groups
{
    public $groupsIds = [0];
    public $parent_id = [];

    private $database = null;
    private $wraps = null;
    private $groupsArray = [];

    public function __construct($groupsIds, Database $database, Wraps $wraps) //gropsIds = 0,1,3
    {
        $this->database = $database;
        $this->wraps = $wraps;

        $this->handleGroupsIds($groupsIds);
        $this->groupsArray = $this->getGroups();
    }

    public function render()
    {
        $this->renderGroupBranch();
    }

    public function handleGroupsIds($groupsIds)
    {
        $groupsIds = explode(',', $groupsIds);
        $groupsIds = array_map('intval', $groupsIds);
        $groupsIds = array_unique($groupsIds);
        asort($groupsIds);

        $this->groupsIds = empty($groupsIds) ? [0] : $groupsIds;
    }

    private function getGroups()
    {
        $groupsIds = $this->getGroupsIdsString();
        $result = $this->database->getGroups($groupsIds);
        $return = [];
        $previousParentId = 0;
        $parentsIds = [0];

        foreach ($result as $value) {
            $parentId = $value['id_parent'];
            $id = $value['id'];

            if ($previousParentId > $parentId) {
                array_pop($parentsIds);
            }

            if ($previousParentId < $parentId) {
                array_push($parentsIds, $parentId);
            }

            $return[$parentId][$id] = [
                'group' => $value,
                'parentsIds' => $parentsIds,
                'products' => $id,
            ];

            $previousParentId = $parentId;
        }

        return $return;
    }

    private function renderGroupBranch($groupId = 0)
    {
        if (!isset($this->groupsArray[$groupId])) {
            return;
        }

        foreach ($this->groupsArray[$groupId] as $value) {
            $currentGroup = $value['group'];
            $parentsIds = $value['parentsIds'];
            $groupIds = $parentsIds;
            $groupIds[] = $currentGroup['id'];
            $groupIds = implode(',', $groupIds);

            $groupUrl = "index.php?groupsIds={$groupIds}";

            // Составление li - a списка
            $tagA = $this->wraps->wrapInTagA($currentGroup['name'], $groupUrl);
            $tagLi = $this->wraps->wrapInTagLi($tagA);

            // Вывод дерева рекурсией
            echo '<ul>';

            echo $tagLi;

            $this->renderGroupBranch($currentGroup['id']);

            echo '</ul>';
        }
    }

    private function getGroupsIdsString()
    {
        $groupsIds = implode(', ', $this->groupsIds);

        return $groupsIds;
    }
}
