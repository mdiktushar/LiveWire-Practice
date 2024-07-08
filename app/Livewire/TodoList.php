<?php

namespace App\Livewire;

use App\Models\Todo;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;


class TodoList extends Component
{
    use WithPagination;
    public $name, $search, $editId, $editName;

    public function create()
    {
        // validate
        $input = $this->validate([
            'name' => 'required|min:3|max:255',
        ]);
        // store in database
        Todo::create($input);
        // clear input
        $this->reset('name');
        // flash message
        session()->flash('success', 'Saved');
    }

    public function toggleComplete(Todo $todo)
    {
        try {
            $todo->compleated = !$todo->compleated;
            $todo->save();
        } catch (Exception $e) {

        }
    }

    public function edit(Todo $todo)
    {
        $this->editId = $todo->id;
        $this->editName = $todo->name;
    }


    public function update(Todo $todo)
    {
        try {
            $todo->name = $this->editName;
            $todo->save();
            $this->editCancle($todo);
        } catch (Exception $e) {

        }

    }

    public function editCancle(Todo $todo)
    {
        $this->editId = null;
        $this->editName = null;
    }

    public function destroy(Todo $todo)
    {
        try {
            $todo->delete();
        } catch (Exception $e) {

        }
    }

    public function render()
    {
        $todos = Todo::where('name', 'like', "%{$this->search}%")->latest()->paginate(3);

        return view('livewire.todo-list', compact('todos'));
    }
}
