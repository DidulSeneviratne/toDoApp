import { mount } from '@vue/test-utils'
import HelloWorld from '../../resources/js/components/HelloWorld.vue'
import { describe, it, expect } from 'vitest'

describe('HelloWorld.vue', () => {
  it('renders message prop', () => {
    const wrapper = mount(HelloWorld, { props: { msg: 'Hello Vue!' } })
    expect(wrapper.text()).toContain('Hello Vue!')
  })

  it('increments count when button is clicked', async () => {
    const wrapper = mount(HelloWorld, { props: { msg: 'Test Count' } })
    await wrapper.find('button').trigger('click')
    expect(wrapper.text()).toContain('Count is 1')
  })
})
