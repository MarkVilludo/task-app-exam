<template>
  <AppLayout page_title="Dashboard" page_icon="fas fa-layer-group">
      <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Tasks</h1>
        <div class="mb-4 flex flex-wrap gap-2 items-center">
          <input v-model="filters.search" @input="fetchTasks" type="text" placeholder="Search by title..." class="border rounded px-2 py-1" />
          <select v-model="filters.status" @change="fetchTasks" class="border rounded px-2 py-1">
            <option value="">All Statuses</option>
            <option value="to-do">To Do</option>
            <option value="in-progress">In Progress</option>
            <option value="done">Done</option>
          </select>
          <select v-model="filters.order_by" @change="fetchTasks" class="border rounded px-2 py-1">
            <option value="created_at">Date Created</option>
            <option value="title">Title</option>
          </select>
          <select v-model="filters.direction" @change="fetchTasks" class="border rounded px-2 py-1">
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
          </select>
          <select v-model="filters.per_page" @change="fetchTasks" class="border rounded px-2 py-1">
            <option :value="10">10</option>
            <option :value="20">20</option>
            <option :value="50">50</option>
          </select>
          <button @click="showCreate = true" class="ml-auto bg-blue-500 text-white px-4 py-2 rounded">+ New Task</button>
        </div>
        <table class="min-w-full bg-white border">
          <thead>
            <tr>
              <th class="border px-4 py-2">Title</th>
              <th class="border px-4 py-2">Status</th>
              <th class="border px-4 py-2">Draft</th>
              <th class="border px-4 py-2">Created</th>
              <th class="border px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="task in tasks.data" :key="task.id">
              <td class="border px-4 py-2">
                {{ task.title }}
                <div v-if="task.subtasks && task.subtasks.length" class="text-xs text-gray-500 mt-1">
                  Subtasks: {{ subtaskProgress(task) }}
                </div>
              </td>
              <td class="border px-4 py-2">{{ task.status }}</td>
              <td class="border px-4 py-2">{{ task.is_draft ? 'Yes' : 'No' }}</td>
              <td class="border px-4 py-2">{{ new Date(task.created_at).toLocaleString() }}</td>
              <td class="border px-4 py-2">
                <button @click="openEdit(task)" class="text-blue-600 hover:underline mr-2">Edit</button>
                <button @click="deleteTask(task)" class="text-red-600 hover:underline mr-2">Delete</button>
                <button v-if="!task.deleted_at" @click="moveToTrash(task)" class="text-yellow-600 hover:underline">Trash</button>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="mt-4 flex justify-between items-center">
          <button :disabled="!tasks.prev_page_url" @click="changePage(tasks.current_page - 1)" class="px-3 py-1 border rounded">Prev</button>
          <span>Page {{ tasks.current_page }} of {{ tasks.last_page }}</span>
          <button :disabled="!tasks.next_page_url" @click="changePage(tasks.current_page + 1)" class="px-3 py-1 border rounded">Next</button>
        </div>

        <!-- Create Task Modal -->
        <div v-if="showCreate" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
          <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Create Task</h2>
            <form @submit.prevent="createTask">
              <div class="mb-2">
                <label class="block">Title</label>
                <input v-model="form.title" type="text" class="border rounded px-2 py-1 w-full" maxlength="100" required />
              </div>
              <div class="mb-2">
                <label class="block">Content</label>
                <textarea v-model="form.content" class="border rounded px-2 py-1 w-full" required></textarea>
              </div>
              <div class="mb-2">
                <label class="block">Status</label>
                <select v-model="form.status" class="border rounded px-2 py-1 w-full" required>
                  <option value="to-do">To Do</option>
                  <option value="in-progress">In Progress</option>
                  <option value="done">Done</option>
                </select>
              </div>
              <div class="mb-2">
                <label class="block">Image (optional, max 4MB)</label>
                <input type="file" @change="onFileChange" accept="image/*" />
              </div>
              <div class="mb-2">
                <label><input type="checkbox" v-model="form.is_draft" /> Save as draft</label>
              </div>
              <div class="mb-2">
                <label class="block">Select Subtasks</label>
                <select v-model="form.subtasks" multiple class="border rounded px-2 py-1 w-full">
                  <option v-for="task in allTasks" :key="task.id" :value="task.id" v-if="!form.id || task.id !== form.id">
                    {{ task.title }}
                  </option>
                </select>
              </div>
              <div class="flex gap-2 mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
                <button type="button" @click="showCreate = false" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
              </div>
              <div v-if="error" class="text-red-500 mt-2">{{ error }}</div>
            </form>
          </div>
        </div>

        <!-- Edit Task Modal -->
        <div v-if="showEdit" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
          <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Edit Task</h2>
            <form @submit.prevent="updateTask">
              <div class="mb-2">
                <label class="block">Title</label>
                <input v-model="editForm.title" type="text" class="border rounded px-2 py-1 w-full" maxlength="100" required />
              </div>
              <div class="mb-2">
                <label class="block">Content</label>
                <textarea v-model="editForm.content" class="border rounded px-2 py-1 w-full" required></textarea>
              </div>
              <div class="mb-2">
                <label class="block">Status</label>
                <select v-model="editForm.status" class="border rounded px-2 py-1 w-full" required>
                  <option value="to-do">To Do</option>
                  <option value="in-progress">In Progress</option>
                  <option value="done">Done</option>
                </select>
              </div>
              <div class="mb-2" v-if="editForm.image_path">
                <label class="block">Current Image</label>
                <img :src="`/storage/${editForm.image_path}`" alt="Task Image" class="h-24 w-24 object-cover rounded border" />
              </div>
              <div class="mb-2">
                <label class="block">Image (optional, max 4MB)</label>
                <input type="file" @change="onEditFileChange" accept="image/*" />
              </div>
              <div class="mb-2">
                <label><input type="checkbox" v-model="editForm.is_draft" /> Save as draft</label>
              </div>
              <div class="mb-2">
                <label class="block">Subtasks</label>
                <div v-if="editForm.subtasks.length">
                  <div v-for="sub in editForm.subtasks" :key="sub.id">
                    {{ sub.title }}
                  </div>
                </div>
                <div v-else class="text-gray-400">No subtasks assigned.</div>
              </div>
              <div class="mb-2">
                <label class="block">Subtasks Status</label>
                <div v-if="editForm.subtasks.length">
                  <div v-for="(sub, idx) in editForm.subtasks" :key="sub.id" class="flex items-center gap-2 mb-1">
                    <span>{{ sub.title }}</span>
                    <select v-model="editForm.subtasks[idx].status" class="border rounded px-1 py-0.5">
                      <option value="to-do">To Do</option>
                      <option value="in-progress">In Progress</option>
                      <option value="done">Done</option>
                    </select>
                  </div>
                </div>
                <div v-else class="text-gray-400">No subtasks assigned.</div>
              </div>
              <div class="flex gap-2 mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
                <button type="button" @click="showEdit = false" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
              </div>
              <div v-if="editError" class="text-red-500 mt-2">{{ editError }}</div>
            </form>
          </div>
        </div>
      </div>
     </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';

const tasks = ref({ data: [], current_page: 1, last_page: 1, prev_page_url: null, next_page_url: null });
const filters = reactive({ search: '', status: '', order_by: 'created_at', direction: 'desc', per_page: 10, page: 1 });
const showCreate = ref(false);
const showEdit = ref(false);
const form = reactive({ title: '', content: '', status: 'to-do', is_draft: false, image: null });
const editForm = reactive({ id: null, title: '', content: '', status: 'to-do', is_draft: false, image: null, image_path: null });
const error = ref('');
const editError = ref('');
const allTasks = ref([]);
const selectedSubtaskIds = ref([]);

function fetchTasks() {
  axios.get('api/tasks', {
    params: { ...filters, page: filters.page }
  })
    .then(res => { tasks.value = res.data; })
    .catch(() => { tasks.value = { data: [] }; });
}

function changePage(page) {
  filters.page = page;
  fetchTasks();
}

function onFileChange(e) {
  form.image = e.target.files[0];
}

function onEditFileChange(e) {
  editForm.image = e.target.files[0];
}

function createTask() {
  error.value = '';
  const data = new FormData();
  data.append('title', form.title);
  data.append('content', form.content);
  data.append('status', form.status);
  data.append('is_draft', form.is_draft ? 1 : 0);
  if (form.image) data.append('image', form.image);
  if (form.subtasks && form.subtasks.length) {
    form.subtasks.forEach(id => data.append('subtasks[]', id));
  }
  axios.post('/api/tasks', data)
    .then(() => {
      showCreate.value = false;
      Object.assign(form, { title: '', content: '', status: 'to-do', is_draft: false, image: null, subtasks: [] });
      fetchTasks();
    })
    .catch(err => {
      error.value = err.response?.data?.message || 'Failed to create task.';
    });
}

function openEdit(task) {
  fetchAllTasks();
  const subtasks = Array.isArray(task.subtasks) ? task.subtasks.map(sub => ({
    id: sub.id,
    title: sub.title,
    status: sub.status
  })) : [];

  console.log(task.subtasks);
  console.log(subtasks);
  Object.assign(editForm, {
    id: task.id,
    title: task.title,
    content: task.content,
    status: task.status,
    is_draft: task.is_draft,
    image: null,
    image_path: task.image_path,
    subtasks
  });
  selectedSubtaskIds.value = subtasks.map(s => s.id);
  showEdit.value = true;
  editError.value = '';
}

function updateTask() {
  editError.value = '';
  axios.put(`/api/tasks/${editForm.id}`, {
    title: editForm.title,
    content: editForm.content,
    status: editForm.status,
    is_draft: editForm.is_draft,
    subtasks: editForm.subtasks.map(sub => sub.id),
    subtask_statuses: Object.fromEntries(editForm.subtasks.map(sub => [sub.id, sub.status])),
    // ...other fields as needed
  })
    .then(() => {
      showEdit.value = false;
      fetchTasks();
    })
    .catch(err => {
      editError.value = err.response?.data?.message || 'Failed to update task.';
    });
}

function deleteTask(task) {
  if (!confirm('Are you sure you want to delete this task?')) return;
  axios.delete(`/api/tasks/${task.id}`)
    .then(fetchTasks)
    .catch(() => {});
}

function moveToTrash(task) {
  // For now, just soft delete (same as deleteTask)
  deleteTask(task);
}

function subtaskProgress(task) {
  if (!Array.isArray(task.subtasks) || !task.subtasks.length) return '';
  const done = task.subtasks.filter(sub => sub.status === 'done').length;
  return `${done}/${task.subtasks.length} done`;
}

function fetchAllTasks() {
  axios.get('/api/tasks', { params: { per_page: 1000 } })
    .then(res => {
      allTasks.value = res.data.data.filter(t => t && t.id !== undefined);
    });
}

watch(
  () => selectedSubtaskIds.value,
  (newIds) => {
    // Keep editForm.subtasks in sync with selectedSubtaskIds
    editForm.subtasks = allTasks.value
      .filter(t => newIds.includes(t.id))
      .map(t => {
        // If already in subtasks, keep its status
        const existing = editForm.subtasks.find(s => s.id === t.id);
        return {
          id: t.id,
          title: t.title,
          status: existing ? existing.status : t.status
        };
      });
  }
);

onMounted(() => {
  fetchTasks();
  fetchAllTasks();
});
</script>

<style scoped>
.container { max-width: 100%; }
</style> 