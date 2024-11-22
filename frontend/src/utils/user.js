import { ref } from 'vue'

export const getUserData = () => {
  const parsedUserData = JSON.parse(localStorage.getItem('user_data') || '{}')
  return ref(parsedUserData)
}
