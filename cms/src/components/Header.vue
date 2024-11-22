  <template>
    <div class="h-16 mb-4 w-full">
      <div class="flex py-4 items-center border-b mb-4">
        <div class="flex justify-between flex-grow">
          <div class="flex ml-6">
            <input
              type="text"
              v-model="searchQuery"
              @input="updateSearch"
              placeholder="Search tutors..."
              class="px-4 border border-gray-300 rounded-lg w-[350px] h-9 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
            />
          </div>
          <div class="flex flex-row items-center mr-6">
            <img
              src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiIGNsYXNzPSJsdWNpZGUgbHVjaWRlLWJlbGwiPjxwYXRoIGQ9Ik02IDhhNiA2IDAgMCAxIDEyIDBjMCA3IDMgOSAzIDlIM3MzLTIgMy05Ii8+PHBhdGggZD0iTTEwLjMgMjFhMS45NCAxLjk0IDAgMCAwIDMuNCAwIi8+PC9zdmc+"
              alt="Bell Icon"
              class="ml-5 w-5 h-5"
            />
            <div class="ml-5 w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
              <span class="text-slate-700 font-bold">P</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, defineEmits } from 'vue';

  const emit = defineEmits(['update:search']);
  const searchQuery = ref('');

  const updateSearch = () => {
    emit('update:search', searchQuery.value);
  };
  </script>
