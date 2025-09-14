<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Debug</title>
    @vite('resources/js/app.js')
</head>
<body>
    <h1>Analytics Debug Page</h1>
    <div id="debug-info"></div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const debugDiv = document.getElementById('debug-info');
        
        // Check if PredictiveAnalytics is available
        if (typeof PredictiveAnalytics !== 'undefined') {
            debugDiv.innerHTML = '<p style="color: green;">✅ PredictiveAnalytics class is available!</p>';
            
            try {
                const analytics = new PredictiveAnalytics();
                debugDiv.innerHTML += '<p style="color: green;">✅ PredictiveAnalytics instance created successfully!</p>';
            } catch (error) {
                debugDiv.innerHTML += '<p style="color: red;">❌ Error creating PredictiveAnalytics instance: ' + error.message + '</p>';
            }
        } else {
            debugDiv.innerHTML = '<p style="color: red;">❌ PredictiveAnalytics class is NOT available</p>';
            debugDiv.innerHTML += '<p>Available global objects: ' + Object.keys(window).filter(key => key.includes('Analytics') || key.includes('TensorFlow')).join(', ') + '</p>';
        }
        
        // Check if TensorFlow is available
        if (typeof tf !== 'undefined') {
            debugDiv.innerHTML += '<p style="color: green;">✅ TensorFlow.js is available!</p>';
            
            // Check if TensorFlow is ready
            tf.ready().then(() => {
                debugDiv.innerHTML += '<p style="color: green;">✅ TensorFlow.js is ready!</p>';
            }).catch(err => {
                debugDiv.innerHTML += '<p style="color: red;">❌ TensorFlow.js failed to initialize: ' + err.message + '</p>';
            });
        } else {
            debugDiv.innerHTML += '<p style="color: red;">❌ TensorFlow.js is NOT available</p>';
        }
    });
    </script>
</body>
</html>
