<template>
  <h1>{{ msg }}</h1>
  <button @click="count++">Count is {{ count }}</button>
</template>

<script>
export default {
  name: "HelloWorld",
  props: {
    msg: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      count: 0
    }
  }
}
</script>
