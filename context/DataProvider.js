import React, { useEffect, useState } from "react";

import DataContext from "./DataContext";

const DataProvider = ({ children }) => {
  const [data, setData] = useState([]);
  console.log("data on all pages: ", data);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const websiteUrl = window.location.hostname;

        const response = await fetch(
          `https://123-movies.world/api/url/${websiteUrl}`
        );
        console.log("response: ", response);
        if (response.ok) {
          const data = await response.json();
          setData(data);
        } else {
          console.error("Error fetching data:", response.status);
        }
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    };
    fetchData();
  }, []);

  return <DataContext.Provider value={data}>{children}</DataContext.Provider>;
};

export default DataProvider;