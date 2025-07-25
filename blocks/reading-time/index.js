const { useSelect } = wp.data;
const { useEntityId } = wp.coreData;

wp.blocks.registerBlockType('lc/time-to-read', {
  edit: () => {
    const postId = wp.data.select('core/editor').getCurrentPostId();
    const [content, setContent] = wp.element.useState('Loading...');

    wp.element.useEffect(() => {

      if (!postId) {
        return;
      }

      wp.apiFetch({ path: `/time-to-read/v1/${postId}` })
        .then((result) => {
          setContent(result || 'Not available');
        })
        .catch(() => setContent('Error loading reading time'));
    }, [postId]);

    return wp.element.createElement('p', null, content);
  },
  save: () => null, // Server-rendered
});