import { mount, flushPromises } from '@vue/test-utils'
import TodoComponent from '../../resources/js/components/TaskList.vue'
import axios from 'axios'

// Mock axios
vi.mock('axios')

describe('TaskList.vue', () => {
  beforeEach(() => {
    vi.clearAllMocks()
  })

  test('fetches tasks on mount', async () => {
    axios.get.mockResolvedValue({
      data: [{ id: 1, title: 'Test Task', completed: false }]
    })

    const wrapper = mount(TodoComponent)
    await flushPromises()
    await wrapper.vm.$nextTick()

    expect(axios.get).toHaveBeenCalledWith('/api/tasks')

    // index 0 = "New Task" input, index 1 = first task's title input
    const taskTitleInput = wrapper.findAll('input[type="text"]')[1]
    expect(taskTitleInput.exists()).toBe(true)
    expect(taskTitleInput.element.value).toBe('Test Task')
  })

  test('creates a task', async () => {
    axios.get.mockResolvedValue({ data: [] })
    axios.post.mockResolvedValue({
      data: { id: 1, title: 'New Task', completed: false }
    })

    const wrapper = mount(TodoComponent)
    await flushPromises()
    await wrapper.vm.$nextTick()

    const newTaskInput = wrapper.find('input[placeholder="New task"]')
    expect(newTaskInput.exists()).toBe(true)

    await newTaskInput.setValue('New Task')
    await newTaskInput.trigger('keyup.enter')
    await flushPromises()
    await wrapper.vm.$nextTick()

    expect(axios.post).toHaveBeenCalledWith('/api/tasks', { title: 'New Task' })

    // After creation, index 1 is now the new task's input
    const taskTitleInput = wrapper.findAll('input[type="text"]')[1]
    expect(taskTitleInput.exists()).toBe(true)
    expect(taskTitleInput.element.value).toBe('New Task')
  })

  test('updates a task', async () => {
    axios.get.mockResolvedValue({
      data: [{ id: 1, title: 'Old Title', completed: false }]
    })
    axios.put.mockResolvedValue({})

    const wrapper = mount(TodoComponent)
    await flushPromises()
    await wrapper.vm.$nextTick()

    // Target the first task's title input (index 1)
    const taskTitleInput = wrapper.findAll('input[type="text"]')[1]
    expect(taskTitleInput.exists()).toBe(true)

    await taskTitleInput.setValue('Updated Title')
    await taskTitleInput.trigger('blur')

    expect(axios.put).toHaveBeenCalledWith('/api/tasks/1', {
      id: 1,
      title: 'Updated Title',
      completed: false
    })
  })

  test('deletes a task', async () => {
    axios.get.mockResolvedValue({
      data: [{ id: 1, title: 'Task to Delete', completed: false }]
    })
    axios.delete.mockResolvedValue({})

    const wrapper = mount(TodoComponent)
    await flushPromises()
    await wrapper.vm.$nextTick()

    await wrapper.find('button').trigger('click')
    expect(axios.delete).toHaveBeenCalledWith('/api/tasks/1')
  })
})