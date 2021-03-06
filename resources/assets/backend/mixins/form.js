import diff from '../utils/diff';
import mixinConfig from './mixinConfig';
export default {
  mixins: [mixinConfig],
  data () {
    return {
      title: '',
      errors: [],
      defaultConfig: {
        editPrefix: '编辑',
        addPrefix: '添加',
        editMethod: 'put',
        addMethod: 'post',
        query: {}, // 附加query参数
        title: '', // prefix + title = 最终标题
        action: ''
      }
    };
  },
  methods: {
    confirm () {
      if (this.$refs.form) {
        this.$refs.form.validate((valid) => {
          if (!valid) {
            this.$Message.error('填写有误!');
          } else {
            this.submit();
          }
        });
      } else {
        this.submit();
      }
    },
    submit () {
      this.$http[this.isAdd() ? this.getConfig('addMethod') : this.getConfig('editMethod')](this.getConfig('action'), diff.diff(this.formData)).then(res => {
        this.$Message.success(`${this.title}成功`);
        this.$emit('on-success');
        this.$emit('on-loaded');
      }).catch(err => {
        this.errors = err.response.data.errors;
        this.$emit('on-loaded');
      });
    },
    isAdd () {
      return this.$route.meta.isAdd;
    },
    init () {
      if (!this.isAdd()) {
        this.$http.get(this.getConfig('action'), {
          params: this.getConfig('query')
        }).then(res => {
          this.formData = res.data.data;
          diff.save(this.formData);
          this.$emit('on-data', this.formData);
        });
        this.title = this.getConfig('editPrefix') + this.getConfig('title');
      } else {
        this.title = this.getConfig('addPrefix') + this.getConfig('title');
      }
    }
  },
  created () {
    this.init();
  }
};
