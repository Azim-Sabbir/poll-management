<div id="poll-root"></div>

<script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
<script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
<script src="https://unpkg.com/@tailwindcss/browser@4"></script>

<script type="text/babel">
    const { useState, useEffect } = React;

    function Poll() {
        const [selectedOption, setSelectedOption] = useState(null);

        const [totalVotes, setTotalVotes] = useState(0);
        const [poll, setPoll] = useState(null);
        const [options, setOptions] = useState([]);
        const [loading, setLoading] = useState(true);
        const handleOptionClick = (id) => {
            setSelectedOption(id);
        };

        const getWidth = (percent) => {
            return {width: `${percent}%`}
        }


        /*equation -> (count / total) * 100 }}%*/

        useEffect(() => {
            const pollId = window.location.pathname.split('/').pop();

            fetch(`/polls/${pollId}`)
                .then(response => response.json())
                .then(data => {
                    setTotalVotes(data.total_votes);
                    setPoll(data.poll);
                    setOptions(data.options);
                })
                .catch(error => {
                    console.error('Error fetching poll data:', error);
                });
        }, []);


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
                                              {option.title} ({option.votes} votes)
                                            </span>
                                        </div>
                                        <span className="text-gray-600">{option.percentage}%</span>
                                    </div>
                                    <div
                                        className={`mt-3 h-2 rounded-full bg-blue-500`}
                                        style={getWidth(option.percentage)}
                                    ></div>
                                </label>
                            </div>
                        ))}
                    </div>

                    <footer className="mt-6 text-center text-sm text-gray-500">
                        <p>{totalVotes} people have voted. Results are updated in real-time.</p>
                    </footer>
                </div>
            </div>
        );
    }

    const root = ReactDOM.createRoot(document.getElementById("poll-root"));
    root.render(<Poll />);
</script>
