<template>
  <div>
    <input name="selectedLessonPackage" type="radio" :value="title">
    <span>{{ title }} - {{ duration }} - {{ price }}</span>
  </div>
</template>

<script>

  export default {

    props: [
      'title', 'duration', 'price'
    ]

  }

</script>