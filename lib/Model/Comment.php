<?php

namespace Model;

class Comment extends AbstractModelClass
{
    /**
     * @return array
     */
    public function getAllApprovedComments() {
        return $this->getAll('WHERE `status` = 1');
    }

    /**
     * @return array
     */
    public function getAllUnapprovedComments() {
        return $this->getAll('WHERE `status` = 0');
    }

    public function approveAllComments() {
        $this->updateSingleField('status', '1', 'WHERE `status` = 0');
    }

    public function denyAllComments() {
        $this->updateSingleField('status', '2', 'WHERE `status` = 0');
    }

    public function approveSingleComment($id) {
        $this->updateSingleField('status', '1', 'WHERE `id` = ' . $id);
    }

    public function denySingleComment($id) {
        $this->updateSingleField('status', '2', 'WHERE `id` = ' . $id);
    }
}