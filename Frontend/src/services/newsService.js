import api from './api';

const newsService = {
    /**
     * Get all news items
     * @returns {Promise} Array of news items
     */
    getAllNews() {
        return api.get('/news');
    },

    /**
     * Get a single news item by ID
     * @param {number} id - News item ID
     * @returns {Promise} News item object
     */
    getNewsById(id) {
        return api.get(`/news/${id}`);
    },

    /**
     * Create a new news item
     * @param {FormData} data - News data (including image file)
     * @returns {Promise} Created news item
     */
    createNews(data) {
        return api.post('/news', data, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
    },

    /**
     * Update an existing news item
     * @param {number} id - News item ID
     * @param {FormData} data - Updated news data
     * @returns {Promise} Updated news item
     */
    updateNews(id, data) {
        // Laravel doesn't support PUT with FormData, so we use POST with _method
        data.append('_method', 'PUT');
        return api.post(`/news/${id}`, data, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
    },

    /**
     * Delete a news item
     * @param {number} id - News item ID
     * @returns {Promise} Deletion confirmation
     */
    deleteNews(id) {
        return api.delete(`/news/${id}`);
    },

    /**
     * Publish a news item (set status to published)
     * @param {number} id - News item ID
     * @returns {Promise} Updated news item
     */
    publishNews(id) {
        return api.patch(`/news/${id}/publish`);
    },

    /**
     * Unpublish a news item (set status to unpublished)
     * @param {number} id - News item ID
     * @returns {Promise} Updated news item
     */
    unpublishNews(id) {
        return api.patch(`/news/${id}/unpublish`);
    },
};

export default newsService;
