import { watch } from 'vue';

/**
 * Watch an object deeply and perform an action when any of its properties are reset.
 * 
 * @param {Object} object_ - The object to watch.
 * @param {Function} action - The action to perform when any property is reset.
 */
export function formValueOnUpdate(object_, action) {
  watch(() => ({ ...object_ }), 
    (newVal, oldVal) => {
      const isFilterReset = Object.keys(object_).some(key => 
        (object_[key] === null && oldVal[key] !== null) || 
        (object_[key] === '' && oldVal[key] !== '') 
      );

      if (isFilterReset) {
        action();
      }
    },
    { deep: true } // Deep watch for nested object properties
  );
}