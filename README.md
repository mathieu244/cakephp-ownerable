# cakephp-ownerable
Can add owner to any record in a cakephp project

- Add a foreign key to a table you want an associated owner (by default it use "owner_id")
* You can modify it by passing ownerField to the behavior configuration
- In the initialize of the Model/Table add the behavior:
```
$this->addBehavior('Owner');
```
- In each controller you want to use it, you need to add a property to TableRegistry associated with your table. "Sample" is an example of TableRegistry name.
```
public function beforeFilter(Event $event){
  parent::beforeFilter($event);
  $this->Sample->current_user_owner_id = $this->current_user->owner_id;
}
```
### Now each save, will keep the owner id specified in the before filter and each find will include this id!
