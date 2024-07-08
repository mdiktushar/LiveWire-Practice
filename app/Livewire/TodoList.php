<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;
use Livewire\WithPagination;


class TodoList extends Component
{
    use WithPagination;
    public $name, $search;

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
    
    public function destroy(Todo $todo) {
        $todo->delete();
    }

    public function render()
    {
        $todos = Todo::where('name', 'like', "%{$this->search}%")->latest()->paginate(3);

        return view('livewire.todo-list', compact('todos'));
    }
}
