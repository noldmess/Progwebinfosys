<?php
namespace Game\Model;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

class GameTable
{
	protected $tableGateway;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}

	public function getGame($id)
	{
		$id  = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	

	public function getGameHash($hash)
	{
		$rowset = $this->tableGateway->select(array('hash' => $hash));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $hash");
		}
		return $row;
	}
	
	public function getHighscore($limit){
		$rowset = $this->tableGateway->select(function (Select $select) use ($limit) {
			$select->columns(array('winner_name', 'wins' => new \Zend\Db\Sql\Expression('COUNT(*)')));
     		$select->where('winner_name IS NOT NULL');
     		$select->order('wins ASC')->limit($limit);
			$select->group('winner_name');
		});
		
		return $rowset;
/*
		$rowset = $this->tableGateway->select()
			->from('game', array('winner_name', 'COUNT(*) as wins'))
			->where('winner_name IS NOT NULL')
			->order()
			->group('winner_name');
			*/
	}

	public function saveGame(Game $game)
	{
		$data = array(
				'user1' => $game->user1,
				'email1'  => $game->email1,
				'choice1'  => $game->choice1,
				
				'user2' => $game->user2,
				'email2'  => $game->email2,
				'choice2'  => $game->choice2,
				
				'winner' => $game->winner,
				'winner_name' => $game->winner_name,
				
				'hash' =>$game->hash,
		);

		$id = (int)$game->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getGame($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
	}
}

