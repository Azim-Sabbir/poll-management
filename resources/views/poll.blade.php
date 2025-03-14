<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div id="poll-root"></div>

<script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
<script src="https://unpkg.com/@tailwindcss/browser@4"></script>

<script type="text/babel">
    const { useState, useEffect } = React;

    function Poll() {
        const [selectedOption, setSelectedOption] = useState(null);

        // Options data
        const options = [
            { id: "opt-1", label: "HTML", percent: "30%", color: "bg-blue-500" },
            { id: "opt-2", label: "Java", percent: "20%", color: "bg-green-500" },
            { id: "opt-3", label: "Python", percent: "40%", color: "bg-yellow-500" },
            { id: "opt-4", label: "jQuery", percent: "10%", color: "bg-red-500" },
        ];

        // Handle option click
        const handleOptionClick = (id) => {
            setSelectedOption(id);
        };

        const getWidth = (percent) => {
            return {width: percent}
        }

        return (
            <div className="min-h-screen bg-gray-100 flex items-center justify-center p-4">
                <div className="w-full max-w-md bg-white rounded-lg shadow-lg p-6">
                    {/* Header */}
                    <header className="text-center mb-6">
                        <h1 className="text-2xl font-bold text-gray-800">Poll UI Design</h1>
                        <p className="text-sm text-gray-600 mt-2">
                            Vote for your favorite programming language and see the results in real-time.
                        </p>
                    </header>

                    {/* Poll Options */}
                    <div className="space-y-4">
                        {options.map((option) => (
                            <div key={option.id}>
                                <input
                                    type="checkbox"
                                    name="poll"
                                    id={option.id}
                                    checked={selectedOption === option.id}
                                    onChange={() => handleOptionClick(option.id)}
                                    className="hidden"
                                />
                                <label
                                    htmlFor={option.id}
                                    className={`block p-4 rounded-lg cursor-pointer transition-all duration-300
                                     border-2 border-blue-500 bg-blue-50
                                     ${
                                        selectedOption === option.id
                                            ? "border-2 border-blue-500 bg-blue-50"
                                            : "border border-gray-200 hover:border-blue-300 hover:bg-gray-50"
                                    }`}
                                    onClick={() => handleOptionClick(option.id)}
                                >
                                    <div className="flex justify-between items-center">
                                        <div className="flex items-center gap-3">
                                            <span
                                                className={`w-5 h-5 rounded-full border-2 ${
                                                    selectedOption === option.id
                                                        ? "border-blue-500"
                                                        : "border-gray-300"
                                                }`}
                                            ></span>
                                            <span className="text-gray-800 font-medium">
                                              {option.label}
                                            </span>
                                        </div>
                                        <span className="text-gray-600">{option.percent}</span>
                                    </div>
                                    {/* Progress Bar */}
                                    <div
                                        className={`mt-3 h-2 rounded-full ${option.color}`}
                                        style={getWidth(option.percent)}
                                    ></div>
                                </label>
                            </div>
                        ))}
                    </div>

                    {/* Footer */}
                    <footer className="mt-6 text-center text-sm text-gray-500">
                        <p>8/10 people have voted. Results are updated in real-time.</p>
                    </footer>
                </div>
            </div>
        );
    }

    const root = ReactDOM.createRoot(document.getElementById("poll-root"));
    root.render(<Poll />);
</script>
</body>
</html>
