<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';

export default {
  components: {
    AuthenticatedLayout,
    Head,
  },

  props:{
    todos: Object
  },

  setup() {
    const form = reactive({
      content: null
    });

    // Submit todo item
    function submit() {
      if (form.content != null && form.content !== "") {
        Inertia.post('/todos', form, {
          onSuccess: () => {
            form.content = ""; // Clear input on success
          },
          onError: () => {
            alert("There was an issue with submitting the todo.");
          }
        });
      }
    }

    // Update the completion status of the todo
    function updateStatus(todo) {
        Inertia.put('/todos/' + todo.id + '/update', {}, {
          onSuccess: () => {
            console.log('Todo updated!');
          }
        });
    }

    // Delete the todo item
    function deleteTodo(todo){
        Inertia.delete('/todos/' + todo.id, {
          onSuccess: () => {
            console.log('Todo deleted!');
          }
        });
    }

    return {
      form,
      submit,
      updateStatus,
      deleteTodo
    };
  },
};
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Todo List
      </h2>
    </template>

    <!-- Main Layout -->
    <div class="h-screen w-full flex items-center justify-center font-sans">
      <div class="bg-white rounded-lg shadow-lg p-5 w-full max-w-4xl"> <!-- Reduced margin-top (mt-2) to bring card closer to header -->
        <div class="mb-5">
          <h1 class="text-3xl font-semibold text-center text-gray-800"><b>TodoList</b></h1>
          <div class="flex mt-4">
            <input
              class="shadow-lg appearance-none border rounded-lg w-full py-3 px-4 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Add Todo"
              v-model="form.content"
            />
            <button
              class="ml-4 px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
              @click="submit"
            >
              Add
            </button>
          </div>
        </div>

        <!-- Todo List -->
        <div>
          <div 
            v-for="todo in todos" 
            :key="todo.id" 
            class="mb-4 flex justify-between items-center p-4 bg-gray-100 rounded-lg shadow-sm hover:shadow-md transition-all"
          >
            <!-- Conditionally apply the 'line-through' class when todo.is_done is true -->
            <p 
              :class="{'line-through text-gray-500': todo.is_done, 'text-gray-700': !todo.is_done}" 
              class="w-full text-lg"
            >
              {{ todo.content }}
            </p>

            <div class="flex items-center space-x-4">
              <!-- Not Done / Done Button -->
              <button
                v-if="todo.is_done"
                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all whitespace-nowrap"
                @click="updateStatus(todo)"
              >
                Not Done
              </button>
              <button
                v-else
                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all"
                @click="updateStatus(todo)"
              >
                Done
              </button>

              <!-- Remove Button -->
              <button
                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all"
                @click="deleteTodo(todo)"
              >
                Remove
              </button>
            </div>
          </div>


        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
